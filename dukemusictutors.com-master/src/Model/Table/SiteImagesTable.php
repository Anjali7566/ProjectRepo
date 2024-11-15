<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SiteImages Model
 *
 * @property \App\Model\Table\DocumentsTable|\Cake\ORM\Association\BelongsTo $Documents
 * @property \App\Model\Table\VolunteerSitesTable|\Cake\ORM\Association\BelongsTo $VolunteerSites
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PublicPostsTable|\Cake\ORM\Association\BelongsTo $PublicPosts
 *
 * @method \App\Model\Entity\SiteImage get($primaryKey, $options = [])
 * @method \App\Model\Entity\SiteImage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SiteImage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SiteImage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SiteImage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SiteImage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SiteImage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SiteImage findOrCreate($search, callable $callback = null, $options = [])
 */
class SiteImagesTable extends Table
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

        $this->belongsTo('Documents')->setJoinType('INNER');
        $this->belongsTo('VolunteerSites')->setJoinType('INNER');
        $this->belongsTo('Users')->setJoinType('INNER');
        $this->belongsTo('PublicPosts')->setJoinType('INNER');
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
            ->allowEmptyFile('id', null, 'create');

        $validator
            ->scalar('description')
            ->maxLength('description', 150)
            ->allowEmptyString('description');

        $validator
            ->boolean('deleted')
            ->notEmptyString('deleted');

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
        $rules->add($rules->existsIn(['volunteer_site_id'], 'VolunteerSites'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['public_post_id'], 'PublicPosts'));

        return $rules;
    }
}
