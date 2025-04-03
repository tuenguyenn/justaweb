<?php
return [
    'post' => [
        'index' => [
            'title' => 'Topic',
            'table' => 'Topic List',
        ],
        'create' => [
            'title' => 'Add New Post',
        ],
        'update' => [
            'title' => 'Edit Post',
        ],
    ],

    'postCatalogue' => [
        'index' => [
            'title' => 'Category',
            'table' => 'Category List',
        ],
        'create' => [
            'title' => 'Add New Category',
            'table' => 'Add New Category',
        ],
        'update' => [
            'title' => 'Update',
            'table' => 'Update Category',
        ],
        'select' => 'Select Category',
    ],

    'productCatalogue' => [
        'index' => [
            'title' => 'Product Category',
            'table' => 'Product Category List',
        ],
        'create' => [
            'title' => 'Add New Product Category',
            'table' => 'Add New Product Category',
        ],
        'update' => [
            'title' => 'Update Product Category',
        ],
        'list' => 'Menu List',
    ],

    'language' => [
        'index' => [
            'title' => 'Language',
            'table' => 'Language List',
        ],
        'create' => [
            'title' => 'Add New Language',
        ],
        'update' => [
            'title' => 'Update Language',
        ],
    ],

    'menu' => [
        'index' => [
            'title' => 'Menu Category',
            'table' => 'Menu Category List',
        ],
        'create' => [
            'title' => 'Add New Menu Category',
            'children' => 'Update Submenu for',
        ],
        'update' => [
            'title' => 'Menu List',
            'table' => 'Update Submenu for',
        ],
        'translate' => [
            'title' => 'Create {language} translation for',
        ],
        'select' => 'Select Category',
    ],

    'product' => [
        'index' => [
            'title' => 'Product',
            'table' => 'Product List',
        ],
        'create' => [
            'title' => 'Add New Product',
            'table' => 'Add New Product',
        ],
        'update' => [
            'title' => 'Update',
            'table' => 'Update Product',
        ],
        'select' => 'Select',
    ],

    'attributeCatalogue' => [
        'index' => [
            'title' => 'Attribute Category',
            'table' => 'Attribute Category List',
        ],
        'create' => [
            'title' => 'Add New Attribute Category',
            'table' => 'Add New Attribute Category',
        ],
        'update' => [
            'title' => 'Update',
            'table' => 'Update Attribute Category',
        ],
        'select' => 'Select',
    ],

    'attribute' => [
        'index' => [
            'title' => 'Attribute',
            'table' => 'Attribute List',
        ],
        'create' => [
            'title' => 'Add New Attribute',
            'table' => 'Add New Attribute',
        ],
        'update' => [
            'title' => 'Update',
            'table' => 'Update Attribute',
        ],
    ],

    'user' => [
        'index' => [
            'title' => 'User Management',
            'table' => 'User List',
        ],
        'create' => [
            'title' => 'Add New',
            'table' => 'Add New User',
        ],
        'update' => [
            'title' => 'Edit User Information',
        ],
    ],

    'customer' => [
        'index' => [
            'title' => 'Customer Management',
            'table' => 'Customer List',
        ],
        'create' => [
            'title' => 'Add New Customer',
        ],
        'update' => [
            'title' => 'Edit Customer Information',
        ],
    ],

    'permission' => [
        'index' => [
            'title' => 'Permission',
            'table' => 'Permission List',
        ],
        'create' => [
            'title' => 'Add New Permission',
            'table' => 'Add New Permission',
        ],
        'update' => [
            'title' => 'Update Information',
        ],
        'name' => 'Permission Name',
        'canonical' => 'Canonical',
    ],

    'userCatalogue' => [
        'index' => [
            'title' => 'User Group Management',
            'table' => 'User Group List',
        ],
        'create' => [
            'title' => 'Add New User Group',
        ],
        'update' => [
            'title' => 'Edit User Group',
        ],
        'permission' => [
            'title' => 'Assign Permission',
        ],
        'selectRole' => 'Select User Group:',
    ],

    'slide' => [
        'index' => [
            'title' => 'Banner/Slide Management',
            'table' => 'Banner/Slide Management',
        ],
        'create' => [
            'title' => 'Add New Slide',
        ],
        'update' => [
            'title' => 'Edit Slide',
        ],
    ],

    'widget' => [
        'index' => [
            'title' => 'Widget Management',
            'table' => 'Widget Management',
        ],
        'create' => [
            'title' => 'Add New Widget',
            'translate' => 'Create {language} translation for',
        ],
        'update' => [
            'title' => 'Edit Widget',
        ],
    ],

    'update_success' => 'Update Successful',
    'update_error' => 'Update Failed',
    'delete_success' => 'Delete Successful',
    'delete_error' => 'Delete Failed',
    'delete' => 'Delete',
    'new' => 'Add New',
    'keyword' => 'Keyword:',
    'cata' => 'Category',
    'parent' => 'Select Parent Category',
    'parentNotice' => 'Select Root if there is no parent category',
    'image' => 'Image',
    'selectStatus' => 'Status:',
    'selectFollow' => 'Navigation:',
    'perpage' => 'Records:',
    'search' => 'Search',
    'searchInput' => 'Enter Keywords to Search...',
    'common' => 'General Information',
    'title' => 'Title',
    'description' => 'Short Description',
    'content' => 'Post Content',
    'seo' => 'SEO Configuration',
    'seoTitle' => 'Enter SEO Title',
    'seo_canonical' => 'https://www.your-url.com/',
    'seo_description' => 'Enter SEO Description',
    'seo_meta_title' => 'SEO Title',
    'seo_meta_keyword' => 'SEO Keywords',
    'seo_meta_desc' => 'Content',
    'canonical' => 'URL Path',
    'tableStatus' => 'Status',
    'tableAction' => 'Action',

    'deleteAction' => 'Confirm Deletion',
    'confirmedDelete' => 'Are you sure you want to delete?',
    'btnCancel' => 'Cancel',
    'btnDelete' => 'Delete',
    'btnSave' => 'Save',
    'dashboard' => 'Dashboard',
    'searchGeneral' => 'Search...',
    'logout' => 'Logout',
    'active' => 'Enabled',
    'unactive' => 'Disabled',
    'publish' => [
        '0' => 'Select Status',
        '1' => 'Inactive',
        '2' => 'Active',
    ],
    'follow' => [
        '0' => 'Select Navigation',
        '1' => 'nofollow',
        '2' => 'follow',
    ],
];
