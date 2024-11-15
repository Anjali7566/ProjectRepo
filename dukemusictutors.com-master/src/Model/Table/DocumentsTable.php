<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use Cake\Datasource\EntityInterface;
use ArrayObject;

/**
 * Documents Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\FoldersTable|\Cake\ORM\Association\BelongsTo $Folders
 *
 * @method \App\Model\Entity\Document get($primaryKey, $options = [])
 * @method \App\Model\Entity\Document newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Document[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Document|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Document saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Document patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Document[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Document findOrCreate($search, callable $callback = null, $options = [])
 */
class DocumentsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->belongsTo('Users')->setJoinType('INNER');
        $this->belongsTo('Folders');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'uploaded' => 'new'
                ]
            ]
        ]);

        $this->hasOne('Receipts')
            ->setJoinType('INNER');
        //FIXME add other relationships
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $extensionsAllowed = ['png', 'jpg'];
        $validator = $this->baselineValidate($validator, $extensionsAllowed);
        return $validator;
    }

    public function validationReceipt(Validator $validator) {
        $extensionsAllowed = ['png', 'jpg', 'pdf'];
        $validator = $this->baselineValidate($validator, $extensionsAllowed);
        return $validator;
    }

    private function baselineValidate(Validator $validator, $extensionsAllowed) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->dateTime('uploaded')
            ->notEmptyDateTime('uploaded');

        $validator
            ->scalar('filename') //why??
            ->maxLength('filename', 40)
            ->requirePresence('filename', 'create')
            ->notEmptyFile('filename', null, true)  //FIXME change true to 'update'?
            //->allowEmptyFile('filanem', null, 'create') FIXME allow empty file so that it can be moved there later?
            ->add('filename', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']); //FIXME folder and file combo unique

        $validator
            ->scalar('extension')
            ->maxLength('extension', 10)
            ->requirePresence('extension', 'create')
            ->notEmptyString('extension');

        //all error messages for extension, filename, temp should happen in filestuff
        $validator = $this->addFileStuff($validator, $extensionsAllowed);

        //FIXME some new name stuff??

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['folder_id'], 'Folders'));

        return $rules;
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
        if (isset($data['filestuff'])) {
            $filestuff = $data['filestuff'];
            $data['temp'] = $filestuff['tmp_name'];
            if (isset($filestuff['new_name']))
                $data['new_name'] = $filestuff['new_name'];
            $originalName = $filestuff['name'];
            if ($this->hasExtension($originalName)) {
                $data['extension'] = $this->getExtension($originalName);
                if (isset($filestuff['new_name']))
                    $data['filename'] = $filestuff['new_name'];
                else if (!isset($data['filename']))
                    $data['filename'] = substr($originalName, 0, strrpos($originalName, '.')); //FIXME allow different name
            }
        }
    }

    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options) {
        //FIXME test
        $this->removeFile($entity); //FIXME remove the file exists check. If this doesn't work the file should just be overwritten
        
        // if (!$this->removeFile($entity)) {
        //     $event->stopPropagation(); //or return false
        //     return false;
        // }
        return true;
    }

    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options) {
        if (!$this->moveFile($entity)) {
            $event->stopPropagation();
            pr('had to stop event before doc save'); //FIXME remove
            return false;
        }
        return true;
    }

    //for uploading new files
    private function addFileStuff(Validator $validator, $extensionsAllowed) {
        $validator
            ->add('filestuff', 'custom', [
                'rule' => function ($value, $context) use ($extensionsAllowed) {
                    $areSet = isset($value['tmp_name']) && isset($value['name']);
                    $emptyStrings = strlen($value['tmp_name']) ===  0 || strlen($value['name']) === 0;
                    if (!$areSet || $emptyStrings)
                        return false;

                    $originalName = $value['name'];
                    $extensionError = $this->extensionErrorMessage($extensionsAllowed);;
                    if (!$this->hasExtension($originalName))
                        return $extensionError;

                    $extension = $this->getExtension($originalName);
                    if (!\in_array($extension, $extensionsAllowed, true))
                        return $extensionError;
                    return true;
                },
                'message' => "File couldn't be uploaded." 
            ]);

        return $validator;
    }

    private function extensionErrorMessage($extensionsAllowed) {
        $extensionsString = $extensionsAllowed[0];
        foreach ($extensionsAllowed as $k => $extension) {
            if ($k == 0)
                continue;
            $extensionsString .= ', ' . $extension;
        }
        return "The uploaded file must have one of the following extensions: " . $extensionsString . ". ";
    }

    private function getExtension($originalName) {
        return strtolower(substr($originalName, strrpos($originalName, '.') + 1));
    }

    private function hasExtension($filename) {
        $dotIndex = strrpos($filename, '.');
        return $dotIndex !== false && $dotIndex < strlen($filename) - 1;
    }

    private function removeFile($entity) {
        return \unlink($entity->full_path);
    }

    private function moveFile($entity) {
        $path = $entity->full_path;
        pr($path); //FIXME remove
        $temp = $entity->temp;
        $success = !is_null($path) && !is_null($temp) && move_uploaded_file($temp, $path);
        // \unlink($entity->temp); FIXME uncomment? Could someone throw in a malicious temp file tho?
        return $success;
    }

    /*

        //$path = "upload/".Security::hash($myname).".".$extension;

        $destinationFolder = $isDirectPath ? $folderName : WWW_ROOT . $folderName;
        $destination = $destinationFolder . $newName . '.' . $extension;
        if (!move_uploaded_file($tmpName, $destination))
            return "File couldn't be saved. ";

        $data = [
            'filename' => $newName,
            'extension' => $extension,
        ];
        $document = $this->Documents->newEntity($data);
        $this->sessionDocuments[] = $document;
        return $document;
        //FIXME handle WWW_REIMBURSEMENTS
        //FIXME handle folders...
    }
    */
}
