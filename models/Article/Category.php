<?php

class Article_Category extends Abstract_Tree
{
	protected $_name = 'article_category';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		'articles' => array(
			'class' => 'Article',
			'type' => 'hasmany',
			'target' => 'category_id',
			'source' => 'id'
		)
	);
}