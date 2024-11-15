<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SiteLocations Model
 *
 * @property \App\Model\Table\VolunteerSitesTable|\Cake\ORM\Association\BelongsTo $VolunteerSites
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\BelongsTo $Locations
 *
 * @method \App\Model\Entity\SiteLocation get($primaryKey, $options = [])
 * @method \App\Model\Entity\SiteLocation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SiteLocation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SiteLocation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SiteLocation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SiteLocation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SiteLocation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SiteLocation findOrCreate($search, callable $callback = null, $options = [])
 */
class SiteLocationsTable extends Table
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

        $this->setTable('site_locations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('VolunteerSites', [
            'foreignKey' => 'volunteer_site_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Locations', [
            'foreignKey' => 'location_id',
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
        $rules->add($rules->existsIn(['volunteer_site_id'], 'VolunteerSites'));
        $rules->add($rules->existsIn(['location_id'], 'Locations'));

        return $rules;
    }
}
