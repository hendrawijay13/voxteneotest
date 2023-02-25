<?php

namespace Drupal\voxcustom\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\file\Entity\File;
use Drupal\Core\Url;


/**
 * Provides a 'News Block' Block.
 *
 * @Block(
 *   id = "news_block",
 *   admin_label = @Translation("News Block"),
 *   category = @Translation("custom_module"),
 * )
 */
class NewsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
  	$nids = \Drupal::entityQuery('node')
		    ->condition('type', 'news')
		    ->condition('status', 1)
		    ->sort('field_publish_date', 'desc')
        	->range(0, 1)
		    ->execute();

	$items = [];

	foreach ($nids as $key => $nid) {
		$node = Node::load($nid);

		$news_title = $node->title->value;
		$news_summary = $node->field_news_description->summary;
		$news_date = $node->field_publish_date->value;

		$news_image_id = $node->field_news_image->target_id;
	    $news_image_uri = File::load($news_image_id)->getFileUri();
	    $news_image_url = \Drupal::service('file_url_generator')->generateAbsoluteString($news_image_uri);

		$items[$key]['nid'] = $nid;
		$items[$key]['title'] = $news_title;
		$items[$key]['summary'] = $news_summary;
		$items[$key]['published_date'] = date("d F Y", strtotime($news_date));
		$items[$key]['image_url'] = $news_image_url;

		$base_path = Url::fromRoute('<front>', [], ['absolute' => TRUE])->toString();
		$items[$key]['node_link'] = $base_path . 'node/' . $nid;
	}

    return [
      '#theme' => 'news_block',
	  '#items' => $items,
    ];
  }

}