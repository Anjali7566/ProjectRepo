<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Document Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $extension
 * @property \Cake\I18n\FrozenTime $uploaded
 * @property int|null $folder_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Folder $folder
 */
class Document extends Entity
{
    private $Folders; //FIXME make static??
    private $folderName;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'filename' => true,
        'extension' => true,
        'uploaded' => true,
        'folder_id' => true,
        'user' => true,
        'folder' => true,

        'temp' => true
    ];

    protected function _getTemp() {
        $key = 'temp';
        $prop = $this->_properties;
        if (isset($prop[$key]))
            return $prop[$key];
        return null;
    }

    protected function _getFullPath() {
        $prop = $this->_properties;
        if (!isset($prop['filename']) || !isset($prop['extension']))
            return null;
        return $this->_getFolderName() . $prop['filename'] . '.' . $prop['extension'];
    }

    protected function _setFullPath() {
        //do nothing
    }

    protected function _getFolderName() {
        if (!is_null($this->folderName))
            return $this->folderName;
        
        $prop = $this->_properties;
        $this->folderName = WWW_IMG;
        if (isset($prop['folder_id'])) {
            $folder = TableRegistry::get('Folders') //FIXME make Folders Table static??
                ->find()
                ->where(['id' => $prop['folder_id']])
                ->first();
            if (!\is_null($folder))
                $this->folderName = $folder->name . '/';
        }
        return $this->folderName;
    }

    protected function _setFolderName() {
        //do nothing
    }
}
