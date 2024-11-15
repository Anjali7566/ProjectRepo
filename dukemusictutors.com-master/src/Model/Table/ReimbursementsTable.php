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
 * Reimbursements Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\VolunteerSitesTable|\Cake\ORM\Association\BelongsTo $VolunteerSites
 *
 * @method \App\Model\Entity\Reimbursement get($primaryKey, $options = [])
 * @method \App\Model\Entity\Reimbursement newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Reimbursement[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Reimbursement|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Reimbursement saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Reimbursement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Reimbursement[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Reimbursement findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReimbursementsTable extends Table
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

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new'
                ]
            ]
        ]);
        $this->belongsTo('Users')
            ->setJoinType('INNER');
        $this->belongsTo('VolunteerSites')
            ->setJoinType('INNER');

        $this->hasMany('OtherRiders')
            ->setDependent(true);
        $this->hasMany('Receipts')
            ->setDependent(true)
            ->setCascadeCallbacks(true);

        $this->addBehavior('SimpleDate');
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
            ->dateTime('submitted')
            ->allowEmptyDateTime('submitted');

        $validator
            ->boolean('deleted')
            ->notEmptyString('deleted');

        $validator
            ->integer('volunteer_site_id', 'Please select the site');

        $validator
            ->add('date_string', 'custom', [
                'rule' => function ($value, $context) {
                    return $this->behaviors()->get('SimpleDate')->isValidDateString($value);
                },
                'message' => 'Date must be of the form m/d (e.g. 5/16)'
            ]);


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
        $rules->add($rules->existsIn(['volunteer_site_id'], 'VolunteerSites'));

        return $rules;
    }

    /*
    
    FIXME add this in a behavior for all tables with string values (beforeMarshal)

    foreach ($data as $key => $value) {
        if (is_string($value)) {
            $data[$key] = trim($value);
        }
    }
    */
}
