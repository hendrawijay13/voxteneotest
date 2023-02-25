<?php

namespace Drupal\voxcustom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Url;


/**
 * Provides a 'Event Block' Block.
 *
 * @Block(
 *   id = "event_block",
 *   admin_label = @Translation("Event Block"),
 *   category = @Translation("custom_module"),
 * )
 */
class EventBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
	public function build() {
		$nids = \Drupal::entityQuery('node')
		    ->condition('type', 'event')
		    ->condition('status', 1)
		    ->condition('field_event_date.value', date("Y-m-d"), '>=')
		    ->range(0, 2)
		    ->sort('field_event_date.value', 'asc')
		    ->execute();

		$items = [];

		foreach ($nids as $key => $nid) {
			$node = Node::load($nid);

			$event_title = $node->title->value;
			$event_summary = $node->field_event_description->summary;
			$event_start_date = $node->field_event_date->value;
			$event_end_date = $node->field_event_date->end_value;

			$event_image_id = $node->field_event_image->target_id;
		    $event_image_uri = File::load($event_image_id)->getFileUri();
		    $event_image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($event_image_uri);

			$items[$key]['nid'] = $nid;
			$items[$key]['title'] = $event_title;
			$items[$key]['summary'] = $event_summary;
			$items[$key]['start_date'] = $event_start_date;
			$items[$key]['end_date'] = $event_end_date;

			if ( !empty($event_end_date) ) {
				$items[$key]['full_date'] = date("d F Y", strtotime($event_start_date)) . " - " . date("d F Y", strtotime($event_end_date));
			} else {
				$items[$key]['full_date'] = date("d F Y", strtotime($event_start_date));
			}
			
			$items[$key]['image_url'] = $event_image_url;

			$base_path = Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
			$items[$key]['node_link'] = $base_path . 'node/' . $nid;
		}

	    return [
	      '#theme' => 'event_block',
		  '#items' => $items,
	    ];
	}

}