<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Privileges Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Privilege get($primaryKey, $options = [])
 * @method \App\Model\Entity\Privilege newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Privilege[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Privilege|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Privilege saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Privilege patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Privilege[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Privilege findOrCreate($search, callable $callback = null, $options = [])
 */
class PrivilegesTable extends Table
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

        $this->setTable('privileges');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->boolean('member_editor')
            ->notEmptyString('member_editor');

        $validator
            ->boolean('treasurer')
            ->notEmptyString('treasurer');

        $validator
            ->boolean('site_manager')
            ->notEmptyString('site_manager');

        $validator
            ->boolean('event_editor')
            ->notEmptyString('event_editor');

        $validator
            ->boolean('moderator')
            ->notEmptyString('moderator');

        $validator
            ->boolean('permanent_deleter')
            ->notEmptyString('permanent_deleter');

        $validator
            ->boolean('email_list_accessor')
            ->notEmptyString('email_list_accessor');

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

        return $rules;
    }
}
