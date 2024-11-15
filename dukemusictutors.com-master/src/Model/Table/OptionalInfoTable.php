<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * OptionalInfo Model
 *
 * @property \App\Model\Table\DocumentsTable|\Cake\ORM\Association\BelongsTo $Documents
 *
 * @method \App\Model\Entity\OptionalInfo get($primaryKey, $options = [])
 * @method \App\Model\Entity\OptionalInfo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OptionalInfo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OptionalInfo|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OptionalInfo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OptionalInfo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OptionalInfo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OptionalInfo findOrCreate($search, callable $callback = null, $options = [])
 */
class OptionalInfoTable extends Table
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
        $this->setDisplayField('user_id');
        $this->setPrimaryKey('user_id');
        $this->belongsTo('Documents');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('user_id')
            ->allowEmptyString('user_id', null, 'create');

        $validator
            ->scalar('bio')
            ->maxLength('bio', 1000)
            ->allowEmptyString('bio');

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
        $rules->add($rules->existsIn(['document_id'], 'Documents'));

        return $rules;
    }
}
