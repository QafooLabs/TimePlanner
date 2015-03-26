<?php

namespace Qafoo\UserBundle\Twig;

use Symfony\Component\HttpFoundation\Request;

class SubRequestExtension extends \Twig_Extension
{
    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'qafoo.user_bundle.sub_request_extension';
    }

    /**
     * Get functions
     *
     * @return \Twig_Function_Function[]
     */
    public function getFunctions()
    {
        return array(
            'is_subrequest' => new \Twig_Function_Method($this, 'isSubrequest'),
        );
    }

    /**
     * Is subrequest
     *
     * @return bool
     */
    public function isSubrequest(Request $request)
    {
        return strpos($request->getPathInfo(), '/_fragment') !== false;
    }
}
