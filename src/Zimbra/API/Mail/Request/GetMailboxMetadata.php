<?php
/**
 * This file is part of the Zimbra API in PHP library.
 *
 * © Nguyen Van Nguyen <nguyennv1981@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zimbra\API\Mail\Request;

use Zimbra\Soap\Request;
use Zimbra\Soap\Struct\SectionAttr;

/**
 * GetMailboxMetadata request class
 *
 * @package   Zimbra
 * @category  API
 * @author    Nguyen Van Nguyen - nguyennv1981@gmail.com
 * @copyright Copyright © 2013 by Nguyen Van Nguyen.
 */
class GetMailboxMetadata extends Request
{
    /**
     * Metadata section specification.
     * @var SectionAttr
     */
    private $_meta;

    /**
     * Constructor method for GetMailboxMetadata
     * @param  SectionAttr $meta
     * @return self
     */
    public function __construct(SectionAttr $meta = null)
    {
        parent::__construct();
        if($meta instanceof SectionAttr)
        {
            $this->_meta = $meta;
        }
    }

    /**
     * Get or set meta
     *
     * @param  SectionAttr $meta
     * @return SectionAttr|self
     */
    public function meta(SectionAttr $meta = null)
    {
        if(null === $meta)
        {
            return $this->_meta;
        }
        $this->_meta = $meta;
        return $this;
    }

    /**
     * Returns the array representation of this class 
     *
     * @return array
     */
    public function toArray()
    {
        if($this->_meta instanceof SectionAttr)
        {
            $this->array += $this->_meta->toArray('meta');
        }
        return parent::toArray();
    }

    /**
     * Method returning the xml representation of this class
     *
     * @return SimpleXML
     */
    public function toXml()
    {
        if($this->_meta instanceof SectionAttr)
        {
            $this->xml->append($this->_meta->toXml('meta'));
        }
        return parent::toXml();
    }
}