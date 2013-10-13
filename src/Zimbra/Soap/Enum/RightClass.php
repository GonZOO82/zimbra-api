<?php
/**
 * This file is part of the Zimbra API in PHP library.
 *
 * © Nguyen Van Nguyen <nguyennv1981@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zimbra\Soap\Enum;

/**
 * RightClass enum class
 * @package   Zimbra
 * @category  Soap
 * @author    Nguyen Van Nguyen - nguyennv1981@gmail.com
 * @copyright Copyright © 2013 by Nguyen Van Nguyen.
 */
class RightClass extends Base
{
    /**
     * Constant for value 'ADMIN'
     * @return string 'ADMIN'
     */
    const ADMIN = 'ADMIN';

    /**
     * Constant for value 'USER'
     * @return string 'USER'
     */
    const USER = 'USER';

    /**
     * Constant for value 'ALL'
     * @return string 'ALL'
     */
    const ALL = 'ALL';
}