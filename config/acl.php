<?php

return [

    /**
     * Model definitions.
     * If you want to use your own model and extend it
     * to package's model. You can define your model here.
     */

    'role'       => 'Kodeine\Acl\Models\Eloquent\Role',
    'permission' => 'App\Permission',

    'role_description' => [
        'admin'

    ],

    /**
     * Most Permissive Wins right
     * If you have multiple permission aliases assigned, each alias
     * has a common permission, view.house => false, but one alias
     * has it set to true. If this right is enabled, true value
     * wins the race, ie the most permissive wins.
     */

    'most_permissive_wins'       => false,

    /**
     * Cache Minutes
     * Set the minutes that roles and permissions will be cached.
     */
		
    'cacheMinutes' => 1,

    'ntfs' => false,

    'default_roles' => [
        'admin' => 'Lorem ipsum dolor amet affogato adaptogen hella, VHS shabby chic umami photo booth.',
        'manager' => 'Woke listicle adaptogen, direct trade brunch you probably havent heard of them messenger bag bespoke.',
        'client' => 'Authentic pug vape ramps, paleo live-edge offal 8-bit celiac crucifix portland af.',
        'member' => 'Gastropub godard sustainable church-key shaman chicharrones.'
    ]
];
