<?php
class User_activity_model extends CI_Model {

  /**
   * Main user activity logging function
   * 
   * @param string $action
   * @param array $arr Additional attributes per case
   * @return void
   */
  public function log($action, $message) {
    $this->add($action, $message);
  }

  /**
   * Adds identifier information to the insert query
   * 
   * @param string $action
   * @param string $message
   * @return void
   */
  private function add($action, $message) {
    $action_data = array(
      'user_name' => $this->admin_session_data['name'],
      'user_id' => $this->admin_session_data['id'],
      'ip' => $this->input->ip_address(),
      'created' => date('Y-m-d H:i:s'),
      'action' => $action,
      'message' => $message
    );

    $this->db->insert('user_activity', $action_data);
  }
}