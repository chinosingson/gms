<?php

/**
 * @file
 * Integrates workflow with entity API.
 */

/**
 * Implements hook_entity_info().
 *
 *  @todo: implement hook_property_info, metadata.
 */
function workflow_entity_info() {
  $entities['WorkflowConfigTransition'] = array(
    'label' => t('Workflow config transition'),
    'entity class' => 'WorkflowConfigTransition',
    // Add controller Class. 'Workflow' class is the de-facto controller.
    'controller class' => 'WorkflowConfigTransitionController',
    'base table' => 'workflow_transitions',
//    'fieldable' => TRUE,
//    'exportable' => TRUE,
    'entity keys' => array(
      'id' => 'tid',
//      'name' => 'tid',
      'status' => 'status',
//      'bundle' => 'type',
    ),
    'label callback' => 'entity_class_label',
    // 'uri callback' => 'entity_class_uri',
    'module' => 'workflow',
//    'access callback' => 'workflow_tab_access', // @todo: use to-be workflow_access here. Access to Tab <> access to workflow. 
  );

  // The Controller class of Transitions and ScheduledTransition is shared.
  $entities['WorkflowTransition'] = array(
    'label' => t('Workflow executed transition'),
    'entity class' => 'WorkflowTransition',
    'controller class' => 'WorkflowTransitionController',
    'base table' => 'workflow_node_history',
    'entity keys' => array(
      'id' => 'hid',
    ),
    'label callback' => 'entity_class_label',
    // 'uri callback' => 'entity_class_uri',
    'module' => 'workflow',
    'views controller class' => 'EntityDefaultViewsController',
  );

  $entities['WorkflowScheduledTransition'] = array(
    'label' => t('Workflow scheduled transition'),
    'entity class' => 'WorkflowScheduledTransition',
    'controller class' => 'WorkflowTransitionController',
    'base table' => 'workflow_scheduled_transition',
    'entity keys' => array(
      'id' => '',
    ),
    'label callback' => 'entity_class_label',
    // 'uri callback' => 'entity_class_uri',
    'module' => 'workflow',
    'views controller class' => 'EntityDefaultViewsController',
  );

  return $entities;
}

/**
 * Entity loader for Workflow.
 *
 * Also used as Menu wild card loader {wildcard_name}_load for '%workflow'.
 * @see http://www.phpgainers.com/content/creating-menu-wildcard-loader-function-drupal-7
 * Used by add-on modules, such as workflow_admin_ui.
 */
function workflow_load($wid, $reset = FALSE) {
  return Workflow::load($wid, $reset);
}

function workflow_load_by_name($name) {
  foreach (Workflow::getWorkflows() as $workflow) {
    if ($name == $workflow->getName()) {
      return $workflow;
    }
  }
  return NULL;
}

function workflow_load_single($wid) {
  $wids = array($wid);
  // $workflows = entity_load('Workflow', $wids);
  $workflows = Workflow::getWorkflows($wids);
  return reset($workflows);
}

function workflow_load_multiple(array $wids = NULL, $reset = FALSE) {
  return Workflow::getWorkflows($wids, $reset);
}

function workflow_create($name) {
  $workflow = Workflow::create($name);
  return $workflow;
}

/**
 * Loads a WorkflowState.
 */
function workflow_state_load($sid) {
  return WorkflowState::load($sid);
}

function workflow_state_load_single($sid) {
  return WorkflowState::load($sid);
}

function workflow_state_load_multiple($wid = 0, $reset = FALSE) {
  return WorkflowState::getStates($wid, $reset);
}

/**
 * Loads a WorkflowTransition.
 */

function workflow_state_load_by_name($sid) {
  return WorkflowState::loadByName($sid);
}

function workflow_transition_load_multiple($entity_type, $entity_id, $field_name = '', $limit = NULL) {
  return WorkflowTransition::loadMultiple($entity_type, $entity_id, $field_name, $limit);
}

function workflow_transition_load_single($entity_type, $entity_id, $field_name = '') {
  $limit = 1;
  if ($transitions = workflow_transition_load_multiple($entity_type, $entity_id, $field_name, $limit)) {
    return reset($transitions);
  }
  return NULL;
}


/*
 * Test functions.
 */
function workflow_entity_test1() {
  $w = Workflow::create('test' . REQUEST_TIME);
  $w->save();
  $s1 = $w->createState('first');
  $s2 = $w->createState('second');
  $s3 = $w->createState('third');
  $s1->save();
  $s2->save();
  $s2->save();
  $s3->save();
  dpm($s1);
  dpm($s2);
  dpm($w->getStates());
  $t1 = $w->createTransition($s1->sid, $s2->sid);
  $t1->save();
  dpm($t1);
  $t2 = $w->createTransition('third', 'second');
  $t2->save();
  $t3 = $w->createTransition('third', 'second');
  dpm($t2);
  dpm($t3);
//  dpm($t->uri());
//  dpm($t->label());
// $w->delete();
}

function workflow_entity_test2() {
  $ts = entity_load('WorkflowConfigTransition', array(6));
  $t = reset($ts);
  $t->roles += array(3 => 3);
  entity_save('WorkflowConfigTransition', $t);
}

function workflow_entity_test3() {
  $w = workflow_load_single(1);
  dpm($w->getStates());
dpm($w->loadTransitions());
//  $t1 = $w->createTransition($s1->sid, $s2->sid);
//  $t1->save();
//  dpm($t1);
//  $t2 = $w->createTransition('third', 'second');
//  $t2->save();
//  dpm($t2);
//  dpm($t->uri());
//  dpm($t->label());
// $w->delete();

//  $ts = entity_load('WorkflowConfigTransition', array(6));
//  $t = reset($ts);
//  $t->roles += array(3 => 3);
//  entity_save('WorkflowConfigTransition', $t);
}

function workflow_entity_allowable() {
  $w = Workflow::create('workflow_test_open');
  $w->save();
  $s1 = $w->createState('to be opened');
  $s2 = $w->createState('open');
  $s3 = $w->createState('closed');
  $s4 = $w->createState('save');
  $s5 = $w->createState('dummy');
  $s1->save();
  $s2->save();
  $s3->save();
  $s4->save();
  $s5->save();
  $roles = array(1, 2); // anon, auth user.
dpm($roles);
  $t1 = $t = $w->createTransition(WORKFLOW_CREATION_STATE_NAME, 'to be opened');
  $t->roles = $roles;
  $t->save();
  $t2 = $t = $w->createTransition('to be opened', 'open');
  $t->roles = $roles;
  $t->save();
  $t3 = $t = $w->createTransition('open', 'closed');
  $t->roles = $roles;
DPM($t3);
  $t->save();
DPM($t3);
  $t4 = $t = $w->createTransition('closed', 'open');
  $t->roles = $roles;
  $t->save();
  $t5 = $t = $w->createTransition(WORKFLOW_CREATION_STATE_NAME, 'dummy');
  $t->roles = $roles;
  $t->save();

  dpm($s2->getOptions('', NULL));
  // returns  TRUE if the role is allowed to do the transition.
  $rc = $t3->isAllowed($role = 2);
  dpm($t3);
  dpm('this result must be TRUE: ' . (int) $rc);
  $rc = $t3->isAllowed($role = 3);
  dpm('this result must be FALSE: ' . (int) $rc);

  $rc = $w->GetTransitionsBySid($s3->sid);
  dpm($rc, 'allowed transitions from ' . $s3->label());
  $rc = $w->GetTransitionsBySid($s4->sid);
  dpm($rc, 'allowed transitions from ' . $s4->label());

  $ts = $w->getTransitions();
  dpm($ts);
  $ts = $w->getTransitionsBySid($s3->sid);
  dpm($ts);
}
