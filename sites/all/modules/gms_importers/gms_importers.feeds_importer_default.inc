<?php
/**
 * @file
 * gms_importers.feeds_importer_default.inc
 */

/**
 * Implements hook_feeds_importer_default().
 */
function gms_importers_feeds_importer_default() {
  $export = array();

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'gms_locations';
  $feeds_importer->config = array(
    'name' => 'GMS Locations',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsFileFetcher',
      'config' => array(
        'allowed_extensions' => 'txt csv tsv xml opml',
        'direct' => FALSE,
        'directory' => 'public://feeds',
        'allowed_schemes' => array(
          0 => 'public',
        ),
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsCSVParser',
      'config' => array(
        'delimiter' => ',',
        'no_headers' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => '1',
        'authorize' => 1,
        'mappings' => array(
          0 => array(
            'source' => 'ID',
            'target' => 'field_project_temp_id',
            'unique' => FALSE,
          ),
          1 => array(
            'source' => 'Lat',
            'target' => 'field_coordinates:lat',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'Lng',
            'target' => 'field_coordinates:lon',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'Lat',
            'target' => 'field_google_coordinates:lat',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'Lng',
            'target' => 'field_google_coordinates:lng',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'NID',
            'target' => 'field_project:etid',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => 'LocTitle',
            'target' => 'title',
            'unique' => 1,
          ),
        ),
        'update_existing' => '1',
        'input_format' => 'plain_text',
        'skip_hash_check' => 1,
        'bundle' => 'location',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '-1',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 0,
  );
  $export['gms_locations'] = $feeds_importer;

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'gms_outputs';
  $feeds_importer->config = array(
    'name' => 'GMS Outputs',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsFileFetcher',
      'config' => array(
        'allowed_extensions' => 'txt csv tsv xml opml',
        'direct' => FALSE,
        'directory' => 'public://feeds',
        'allowed_schemes' => array(
          0 => 'public',
        ),
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsCSVParser',
      'config' => array(
        'delimiter' => ',',
        'no_headers' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => '1',
        'authorize' => 1,
        'mappings' => array(
          0 => array(
            'source' => 'Code',
            'target' => 'field_output_code',
            'unique' => FALSE,
          ),
          1 => array(
            'source' => 'Output / Performance Indicator',
            'target' => 'body',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'OutputTitle',
            'target' => 'title',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'ProjNID',
            'target' => 'field_project:etid',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '0',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => 'output',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '-1',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 0,
  );
  $export['gms_outputs'] = $feeds_importer;

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'gms_projects';
  $feeds_importer->config = array(
    'name' => 'GMS Projects',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsFileFetcher',
      'config' => array(
        'allowed_extensions' => 'txt csv tsv xml opml',
        'direct' => FALSE,
        'directory' => 'public://feeds',
        'allowed_schemes' => array(
          0 => 'public',
        ),
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsCSVParser',
      'config' => array(
        'delimiter' => ',',
        'no_headers' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => '1',
        'authorize' => 1,
        'mappings' => array(
          0 => array(
            'source' => 'ID',
            'target' => 'field_project_temp_id',
            'unique' => FALSE,
          ),
          1 => array(
            'source' => 'Type (main)',
            'target' => 'field_project_type',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'Type (sub)',
            'target' => 'field_project_subtype',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'Project Number',
            'target' => 'field_project_number',
            'unique' => FALSE,
          ),
          4 => array(
            'source' => 'Status',
            'target' => 'field_project_status',
            'unique' => FALSE,
          ),
          5 => array(
            'source' => 'T/G/L No.',
            'target' => 'field_project_tgl_no',
            'unique' => FALSE,
          ),
          6 => array(
            'source' => 'ADB Sector',
            'target' => 'field_adb_sector',
            'unique' => FALSE,
          ),
          7 => array(
            'source' => 'ADB Primary Subsector',
            'target' => 'field_adb_primary_subsector',
            'unique' => FALSE,
          ),
          8 => array(
            'source' => 'Project Name',
            'target' => 'title',
            'unique' => FALSE,
          ),
          9 => array(
            'source' => 'Country',
            'target' => 'field_country',
            'unique' => FALSE,
          ),
          10 => array(
            'source' => 'Appoval Date',
            'target' => 'field_project_approval_date:start',
            'unique' => FALSE,
          ),
          11 => array(
            'source' => 'Closing Date',
            'target' => 'field_project_closing_date:start',
            'unique' => FALSE,
          ),
          12 => array(
            'source' => 'Cost (Total)',
            'target' => 'field_project_cost_total',
            'unique' => FALSE,
          ),
          13 => array(
            'source' => 'Cost (ADB)',
            'target' => 'field_project_cost_adb',
            'unique' => FALSE,
          ),
          14 => array(
            'source' => 'Cost (ADB-TASF)',
            'target' => 'field_project_cost_adb_tasf',
            'unique' => FALSE,
          ),
          15 => array(
            'source' => 'Cost (ADB-JSF/JFPR)',
            'target' => 'field_project_cost_adb_jsf_jfpr',
            'unique' => FALSE,
          ),
          16 => array(
            'source' => 'Cost (Government)',
            'target' => 'field_project_cost_government',
            'unique' => FALSE,
          ),
          17 => array(
            'source' => 'Cost (Cofinancing)',
            'target' => 'field_project_cost_cofinancing_',
            'unique' => FALSE,
          ),
          18 => array(
            'source' => 'Source Cofinancing',
            'target' => 'field_project_source_cofinancing',
            'unique' => FALSE,
          ),
          19 => array(
            'source' => 'ADB Website',
            'target' => 'field_project_adb_website',
            'unique' => FALSE,
          ),
          20 => array(
            'source' => 'Geographical Location',
            'target' => 'field_project_geog_locn',
            'unique' => FALSE,
          ),
          21 => array(
            'source' => 'Start',
            'target' => 'field_project_year_start:start',
            'unique' => FALSE,
          ),
          22 => array(
            'source' => 'End',
            'target' => 'field_project_year_end:start',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '0',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => 'project',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '-1',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 0,
  );
  $export['gms_projects'] = $feeds_importer;

  $feeds_importer = new stdClass();
  $feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
  $feeds_importer->api_version = 1;
  $feeds_importer->id = 'impact_stories';
  $feeds_importer->config = array(
    'name' => 'GMS Impact Stories',
    'description' => '',
    'fetcher' => array(
      'plugin_key' => 'FeedsFileFetcher',
      'config' => array(
        'allowed_extensions' => 'txt csv tsv xml opml',
        'direct' => FALSE,
        'directory' => 'public://feeds',
        'allowed_schemes' => array(
          0 => 'public',
        ),
      ),
    ),
    'parser' => array(
      'plugin_key' => 'FeedsCSVParser',
      'config' => array(
        'delimiter' => ',',
        'no_headers' => 0,
      ),
    ),
    'processor' => array(
      'plugin_key' => 'FeedsNodeProcessor',
      'config' => array(
        'expire' => '-1',
        'author' => '1',
        'authorize' => 1,
        'mappings' => array(
          0 => array(
            'source' => 'Code',
            'target' => 'field_impact_story_code',
            'unique' => FALSE,
          ),
          1 => array(
            'source' => 'ImpactTitle',
            'target' => 'title',
            'unique' => FALSE,
          ),
          2 => array(
            'source' => 'Description',
            'target' => 'body',
            'unique' => FALSE,
          ),
          3 => array(
            'source' => 'ProjNID',
            'target' => 'field_project:etid',
            'unique' => FALSE,
          ),
        ),
        'update_existing' => '0',
        'input_format' => 'plain_text',
        'skip_hash_check' => 0,
        'bundle' => 'impact_story',
      ),
    ),
    'content_type' => '',
    'update' => 0,
    'import_period' => '-1',
    'expire_period' => 3600,
    'import_on_create' => 1,
    'process_in_background' => 0,
  );
  $export['impact_stories'] = $feeds_importer;

  return $export;
}
