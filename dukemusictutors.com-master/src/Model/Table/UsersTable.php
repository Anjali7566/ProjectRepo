<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\UniquesTable|\Cake\ORM\Association\BelongsTo $Uniques
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
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
        $this->setDisplayField('full_name');
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new'
                ]
            ]
        ]);
        $this->belongsTo('Locations');
        $this->hasOne('Privileges');
        $this->belongsToMany('SiteUsers')->setJoinType('INNER'); //FIXME should the join table have an ID?
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
            ->scalar('password')
            ->maxLength('password', 40)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $uniqueIdMsg = 'Must be 7 digits long';
        $validator
            ->numeric('unique_id', $uniqueIdMsg)
            ->maxLength('unique_id', 7, $uniqueIdMsg)
            ->minLength('unique_id', 7, $uniqueIdMsg);

        $validator
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');
        
        $validator = $this->getUsernameValidator($validator);

        return $validator;
    }

    private function getUsernameValidator(Validator $validator) {
        $validator = $validator
            ->maxLength('username', 64)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $isAdmin = $this->find()->count() == 0;
        if (!$isAdmin)
            $validator = $validator->email('username');
               
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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['location_id'], 'Locations'));

        return $rules;
    }
}
