<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SiteUsers Model
 *
 * @property \App\Model\Table\VolunteerSitesTable|\Cake\ORM\Association\BelongsTo $VolunteerSites
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SiteUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\SiteUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SiteUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SiteUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SiteUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SiteUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SiteUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SiteUser findOrCreate($search, callable $callback = null, $options = [])
 */
class SiteUsersTable extends Table
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
        $this->setDisplayField('volunteer_site_id');
        $this->setPrimaryKey(['volunteer_site_id', 'user_id']);
        $this->belongsTo('VolunteerSites')->setJoinType('INNER');
        $this->belongsTo('Users')->setJoinType('INNER');
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
        $rules->add($rules->existsIn(['volunteer_site_id'], 'VolunteerSites'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
