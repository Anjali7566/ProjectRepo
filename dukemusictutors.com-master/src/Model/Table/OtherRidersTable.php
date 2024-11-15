<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


/**
 * OtherRiders Model
 *
 * @property \App\Model\Table\ReimbursementsTable|\Cake\ORM\Association\BelongsTo $Reimbursements
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\OtherRider get($primaryKey, $options = [])
 * @method \App\Model\Entity\OtherRider newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OtherRider[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OtherRider|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OtherRider saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OtherRider patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OtherRider[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OtherRider findOrCreate($search, callable $callback = null, $options = [])
 */
class OtherRidersTable extends Table
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

        $this->setTable('other_riders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Reimbursements')
            ->setJoinType('INNER');
        $this->belongsTo('Users')
            ->setJoinType('INNER');
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
        $rules->add($rules->existsIn(['reimbursement_id'], 'Reimbursements'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
