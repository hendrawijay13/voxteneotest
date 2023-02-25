<?php

namespace Drupal\voxcustom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;

/**
 * Provides a 'Related Article Block' Block.
 *
 * @Block(
 *   id = "related_article_block",
 *   admin_label = @Translation("Related Article Block"),
 *   category = @Translation("custom_module"),
 * )
 */
class RelatedArticleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

  	$current_node = \Drupal::routeMatch()->getParameter('node');
	$items = [];

	if (!empty($current_node)) {
		// $nid = $node->id();
		$category_entity = $current_node->get('field_category')->referencedEntities();
		$category = [] ;

		foreach ($category_entity as $key => $cat) {
			$category[] =  $cat->tid->getString();
		}

		if ($current_node->getType() == "news") {
			$nids = \Drupal::entityQuery('node')
			    ->condition('type', 'news')
			    ->condition('status', 1)
			    ->condition('field_category', $category , 'IN')
			    ->condition('nid', $current_node->id() , '<>')
			    ->sort('field_publish_date', 'desc')
	        	->range(0, 2)
			    ->execute();
		} else if ($current_node->getType() == "event") {
			$nids = \Drupal::entityQuery('node')
			    ->condition('type', 'event')
			    ->condition('status', 1)
			    ->condition('field_event_date.value', date("Y-m-d"), '>=')
			    ->condition('field_category', $category , 'IN')
			    ->condition('nid', $current_node->id() , '<>')
			    ->range(0, 2)
			    ->sort('field_event_date.value', 'asc')
			    ->execute();
		}
		
		foreach ($nids as $key => $nid) {
			$node = Node::load($nid);

			$title = $node->title->value;

			if ($node->getType() == "news") {
				$news_date = $node->field_publish_date->value;

				$news_image_id = $node->field_news_image->target_id;
			    $news_image_uri = File::load($news_image_id)->getFileUri();
			    $news_image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($news_image_uri);


			    $items[$key]['date'] = date("d F Y", strtotime($news_date));
			    $items[$key]['image_url'] = $news_image_url;

			} else if ($node->getType() == "event") {
				$event_start_date = $node->field_event_date->value;
				$event_end_date = $node->field_event_date->end_value;

				$event_image_id = $node->field_event_image->target_id;
			    $event_image_uri = File::load($event_image_id)->getFileUri();
			    $event_image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($event_image_uri);
			
			    if ( !empty($event_end_date) ) {
					$items[$key]['date'] = date("d F Y", strtotime($event_start_date)) . " - " . date("d F Y", strtotime($event_end_date));
				} else {
					$items[$key]['date'] = date("d F Y", strtotime($event_start_date));
				}
				$items[$key]['image_url'] = $event_image_url;
			}
			
			$items[$key]['nid'] = $nid;
			$items[$key]['title'] = $title;
			$items[$key]['node_link'] = \Drupal::service('path_alias.manager')->getAliasByPath('/node/'.$nid);
		}

	    return [
	      '#theme' => 'related_article_block',
		  '#items' => $items,
	    ];

    }
  }

}