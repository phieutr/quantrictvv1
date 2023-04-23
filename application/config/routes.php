<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Project
$route['project']        = 'project/project';
$route['project/add']    = 'project/add_project';
$route['project/edit']   = 'project/edit_project';
$route['project/delete'] = 'project/delete_project';
$route['project/add_ctv'] = 'project/add_ctv';
$route['project/delete_ctv'] = 'project/project_delete_ctv';

//Pay
$route['pay']                 = 'pay/pay';
//Keyword Type
$route['keyword-type']        = 'keyword_type/keyword_type';
$route['keyword-type/add']    = 'keyword_type/add_keyword_type';
$route['keyword-type/edit']   = 'keyword_type/edit_keyword_type';
$route['keyword-type/delete'] = 'keyword_type/delete_keyword_type';

//Keyword
$route['keyword']           = 'keyword/keyword';
$route['keyword-choose']    = 'keyword/keyword_choose';
$route['image-list']        = 'keyword/image_list';

$route['keyword/give']      = 'keyword/give_keyword';
$route['keyword/approve']   = 'keyword/approve_keyword';
$route['keyword/note']      = 'keyword/note_keyword';
$route['keyword/customer-note']   = 'keyword/customer_note_keyword';
$route['keyword/point']     = 'keyword/point_keyword';
$route['keyword/add']       = 'keyword/add_keyword';
$route['keyword/edit']      = 'keyword/edit_keyword';
$route['keyword/choose']    = 'keyword/choose_keyword';
$route['keyword/pcn']    = 'keyword/pcn_keyword';
$route['keyword/image']     = 'keyword/image_upload';
$route['keyword/excel']     = 'keyword/excel_keyword';
$route['keyword/delete']    = 'keyword/delete_keyword';
$route['keyword/edit-info']    = 'keyword/edit_keyword_info';
$route['keyword/auto-update']    = 'keyword/auto_update';
$route['keyword/word-count']    = 'keyword/word_count';
$route['keyword/choose-keyword-edit']    = 'keyword/choose_keyword_edit';

$route['keyword/upload-excel']     = 'keyword/upload_excel';

//Post
$route['post/update/(:any)']    = 'keyword/post_update/$1';
$route['preview/(:any)']        = 'keyword/preview/$1';

//Customer
$route['customer']          = 'customer/customer';
$route['customer/delete']   = 'customer/delete_user';
$route['customer/edit']     = 'customer/edit_user';

//CTV
$route['ctv']          = 'ctv/ctv';
$route['ctv/delete']   = 'ctv/delete_user';
$route['ctv/edit']     = 'ctv/edit_user';
$route['add-user']     = 'ctv/add_user';
$route['browse']     = 'ctv/browse';
$route['details']     = 'ctv/details';
$route['browse_ctv']     = 'ctv/browse_ctv';
$route['del_ctv']     = 'ctv/del_ctv';

$route['ctv_timeline']     = 'ctv_timeline/timeline';

//Login
$route['login']         = 'users/login';
$route['logout']        = 'users/logout';
//Register
$route['register']         = 'users/register';
$route['insert_register']         = 'Register/insert_register';
//Dashboard
$route['dashboard']             = 'dashboard/index';
//Mac dinh
$route['default_controller']    = 'dashboard/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
//Note
$route['insert']          = 'NoteJs/insert';
$route['fetch']          = 'NoteJs/fetch';
$route['fetchh']          = 'NoteJs/fetchh';
$route['delete']          = 'NoteJs/delete';
$route['edit']          = 'NoteJs/edit';
$route['update']          = 'NoteJs/update';
$route['textContent']          = 'NoteJs/textContent';
$route['check']          = 'NoteJs/check';
$route['complete_note']          = 'NoteJs/complete_note';
$route['undoNotes']          = 'NoteJs/undoNotes';

//PCN
$route['pcn']          = 'pcn/pcn';
$route['assigNment']          = 'pcn/assigNment';
$route['addUsers']          = 'pcn/addUsers';