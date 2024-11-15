<?php
    echo $this->Html->css(['blue-background', 'users/account']);
?>
<?php 
    if (is_null($user)) {
        echo $this->element('users/buttongrid', [
            'title' => 'Please sign in',
            'buttons' => [
                ['name' => 'Login', 'controller' => 'Users', 'action' => 'login'],
                ['name' => 'Create Account', 'controller' => 'Users', 'action' => 'create'],
            ]
        ]);
    }
    else {
        echo $this->element('users/buttongrid', [
            'title' => 'Hey, ' . $user['first_name'] . '!',
            'buttons' => [
                ['name' => 'My Reimbursements', 'controller' => 'Reimbursements', 'action' => 'index'],
                //['name' => 'My Blogs', 'controller' => 'Blogs', 'action' => 'index'], //FIXME user-specific index
                //['name' => 'My Images', 'controller' => 'SiteImages', 'action' => 'index'], //FIXME user-specific index
            ]
        ]);

        $privileges = $user['privilege'];
        $memberEditor = $privileges['member_editor'];
        $treasurer = $privileges['treasurer'];
        $siteManager = $privileges['site_manager'];
        $eventEditor = $privileges['event_editor'];
        $moderator = $privileges['moderator'];
        $permanentDeleter = $privileges['permanent_deleter'];
        $emailListAccessor = $privileges['email_list_accessor'];
        if ($memberEditor || $treasurer || $siteManager || $eventEditor || $moderator || $permanentDeleter || $emailListAccessor) {
            //FIXME echo a line break

            $managementButtons = [];
            // if ($emailListAccessor)
            //     $managementButtons[] = ['name' => 'Email Lists', 'controller' => 'FIXME', 'action' => 'FIXME'];
            // if ($memberEditor)
            //     $managementButtons[] = ['name' => 'Members', 'controller' => 'Users', 'action' => 'FIXME'];
            if ($treasurer)
                $managementButtons[] = ['name' => 'Club Reimbursements', 'controller' => 'Reimbursements', 'action' => 'all'];
            // if ($siteManager)
            //     $managementButtons[] = ['name' => 'Volunteer Sites', 'controller' => 'VolunteerSites', 'action' => 'FIXME'];
            // if ($eventEditor)
            //     $managementButtons[] = ['name' => 'Manage Events', 'controller' => 'Events', 'action' => 'FIXME'];
            // if ($moderator)
            //     $managementButtons[] = ['name' => 'Moderate Posts', 'controller' => 'FIXME', 'action' => 'FIXME'];
            // if ($permanentDeleter)
            //     $managementButtons[] = ['name' => 'Clean Database', 'controller' => 'FIXME', 'action' => 'FIXME'];

            echo $this->element('users/buttongrid', [
                'title' => 'Admin',
                'buttons' => $managementButtons
            ]);
        }

        echo $this->element('users/buttongrid', [
            'title' => 'Account',
            'buttons' => [
                // ['name' => 'Edit Basic Info', 'controller' => 'Users', 'action' => 'edit'],
                // ['name' => 'Edit Extra Info', 'controller' => 'Users', 'action' => 'editExtra'],
                ['name' => 'Logout', 'controller' => 'Users', 'action' => 'logout'],
            ],
        ]);
    }
?>