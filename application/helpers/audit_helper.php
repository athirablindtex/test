<?php defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('audit_log')) {

    function audit_log($entity, $entity_id, $action='INSERT', $old_data = null,$new_data = null,$is_show=1)
    {
        $CI =& get_instance();

        $CI->load->database();
        $CI->load->library('session');

        $userId = $CI->session->userdata('admin_id') ?? null;


        $adminUser = null;

if ($userId) {
    $adminUser = $CI->db
        ->select('id, name, email')
        ->where('id', $userId)
        ->get('admin_users')
        ->row_array();
}
$userName = $adminUser['name'] ?? 'Unknown User';

$module = ucwords(str_replace('_', ' ', $entity));
//

   $changeText = '';

        if ($action === 'UPDATE' && is_array($old_data) && is_array($new_data)) {
            $changes = [];

            foreach ($new_data as $key => $newValue) {
                if (!array_key_exists($key, $old_data)) {
                    continue;
                }

                $oldValue = $old_data[$key];

                if ($oldValue != $newValue) {
                    $label = ucwords(str_replace('_', ' ', $key));
                    $changes[] = "{$label}: {$oldValue} → {$newValue}";
                }
            }

            if (!empty($changes)) {
                $changeText = ' | ' . implode(' | ', $changes);
            }
        }


switch ($action) {
    case 'INSERT':
        $note = "New data added in {$module} module by {$userName}";
        break;

    case 'UPDATE':
  $note = "Data updated in {$module} module by {$userName}{$changeText}";
        break;

    case 'DELETE':
        $note = "Data deleted from {$module} module by {$userName}";
        break;
    case 'ENABLE':
        $note = "Record enabled in {$module} module by {$userName}";
        break;
    case 'DISABLE':
        $note = "Record disabled in {$module} module by {$userName}";

    default:
        $note = "Action {$action} performed in {$module} module by {$userName}";
        break;
}

        $CI->db->insert('userhistory', [
            'entity'      => $entity,
            'entity_id'   => $entity_id,
            'action'      => $action,
            'old_data'    => $old_data ? json_encode($old_data) : null,
            'new_data'    => $new_data ? json_encode($new_data) : null,
            'created_at' => date('Y-m-d H:i:s'),
            'note'        => $note, 
            'user_id'    => $userId,
            'is_show'=>$is_show ?? 1
        ]);
    }
}
