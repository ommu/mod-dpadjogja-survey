<?php
/**
 * survey module definition class
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 2 December 2019, 21:35 WIB
 * @link https://github.com/ommu/dpadjogja-survey
 *
 */

namespace dpadjogja\survey;

use Yii;

class Module extends \app\components\Module
{
	public $layout = 'main';

	/**
	 * {@inheritdoc}
	 */
	public $controllerNamespace = 'dpadjogja\survey\controllers';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
        parent::init();
	}
}
