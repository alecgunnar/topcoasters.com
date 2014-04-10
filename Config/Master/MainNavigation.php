<?php

return array('public'     => array('Home'     => '/',
                                   'Forums'   => '/forums',
                                   'Database' => '/database',
                                   'Exchange' => '/exchange'),
             'admin'      => array('Dashboard' => array('/admin', array('Overview'              => '/admin',
                                                                        'Change Offline Status' => '/admin/offline-status')),
                                   'Members'   => array('/admin/members', array('View All Members'      => '/admin/members',
                                                                                'Manage Banned Members' => '/admin/members/banned')),
                                   'Database'  => array('/admin/database', array()),
                                   'Content'   => array('/admin/content', array('Content Overview'        => '/admin/content',
                                                                                'Manage Cached Content'   => '/admin/content/cached',
                                                                                'Manage Reported Content' => '/admin/content/reported')),
                                   'Sign Out'  => array('/admin/sign-out', array())));