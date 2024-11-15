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
 * Receipts Model
 *
 * @property \App\Model\Table\ReimbursementsTable|\Cake\ORM\Association\BelongsTo $Reimbursements
 * @property \App\Model\Table\DocumentsTable|\Cake\ORM\Association\BelongsTo $Documents
 *
 * @method \App\Model\Entity\Receipt get($primaryKey, $options = [])
 * @method \App\Model\Entity\Receipt newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Receipt[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Receipt|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Receipt saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Receipt patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Receipt[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Receipt findOrCreate($search, callable $callback = null, $options = [])
 */
class ReceiptsTable extends Table
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
        $this->setDisplayField('reimbursement_id');
        $this->belongsTo('Reimbursements')
            ->setJoinType('INNER');
        $this->belongsTo('Documents')
            ->setJoinType('INNER')
            ->setDependent(true);
            //->setCascadeCallbacks(true); //doesn't work if cascaded from reimbursements
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
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount')
            ->add('amount', 'custom', [
                'rule' => function ($value, $context) {
                    return is_numeric($value) && 0.0 + $value < 1000 && 0.0 + $value > 0;
                },
                'message' => 'Amount must be greater than $0 and less than $1,000'
            ]);

        $validator
            ->boolean('approved')
            ->notEmptyString('approved');

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
        $rules->add($rules->existsIn(['document_id'], 'Documents'));

        return $rules;
    }

    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options) {
        $this->Documents->delete($this->Documents->get($entity->document_id));
    }
}
