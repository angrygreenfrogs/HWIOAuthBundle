<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HWI\Bundle\OAuthBundle\OAuth\ResourceOwner;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * MapMyRunResourceOwner
 *
 * @author Fabian Kiss <fabian.kiss@ymc.ch>
 */
class MapMyRunResourceOwner extends GenericOAuth2ResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'identifier' => 'id',
        'nickname'   => 'username',
        'realname'   => 'display_name',
        'email'      => 'email',
    );
    
    /**
     * {@inheritDoc}
     */
    protected function configureOptions(OptionsResolverInterface $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'authorization_url' => 'https://www.mapmyfitness.com/v7.1/oauth2/uacf/authorize',
            'access_token_url'  => 'https://www.mapmyfitness.com/v7.1/oauth2/uacf/access_token',
            'infos_url'         => 'https://oauth2-api.mapmyapi.com/v7.1/user/self',
            'scope'             => '',
        ));
    }
    
    /**
     * {@inheritDoc}
     */
    public function getUserInformation(array $accessToken, array $extraParameters = array())
    {
        $url = $this->normalizeUrl($this->options['infos_url']);
        $content = $this->httpRequest($url, null, array('Api-Key: '.$this->options['client_id'],'Authorization: Bearer '.$accessToken['access_token']));

        $response = $this->getUserResponse();
        $response->setResponse($content->getContent());
        $response->setResourceOwner($this);
        $response->setOAuthToken(new OAuthToken($accessToken));

        return $response;
    }
}
