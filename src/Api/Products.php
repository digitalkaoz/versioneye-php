<?php

namespace Rs\VersionEye\Api;

/**
 * Products API
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 * @see https://www.versioneye.com/api/v2/swagger_doc/products
 */
class Products extends BaseApi implements Api
{
    /**
     * search packages
     *
     * @param  string $query
     * @param  string $language
     * @param  string $group
     * @param  int    $page
     * @return array
     */
    public function search($query, $language = null, $group = null, $page = null)
    {
        $url = sprintf('products/search/%s?%s', $query, http_build_query(array(
            'lang' => $language,
            'g' => $group,
            'page' => $page
        )));

        return $this->request($url);
    }

    /**
     * detailed information for specific package
     *
     * @param  string $language
     * @param  string $product
     * @return array
     */
    public function show($language, $product)
    {
        return $this->request(sprintf('products/%s/%s', $language, $this->transform($product)));
    }

    /**
     * check your following status
     *
     * @param  string $language
     * @param  string $product
     * @return array
     */
    public function followStatus($language, $product)
    {
        return $this->request(sprintf('products/%s/%s/follow', $language, $this->transform($product)));
    }

    /**
     * follow your favorite software package
     *
     * @param  string $language
     * @param  string $product
     * @return array
     */
    public function follow($language, $product)
    {
        return $this->request(sprintf('products/%s/%s/follow', $language, $this->transform($product)), 'POST');
    }

    /**
     * unfollow given software package
     *
     * @param  string $language
     * @param  string $product
     * @return array
     */
    public function unfollow($language, $product)
    {
        return $this->request(sprintf('products/%s/%s/follow', $language, $this->transform($product)), 'DELETE');
    }

    /**
     * references
     *
     * @param  string $language
     * @param  string $product
     * @param  int    $page
     * @return array
     */
    public function references($language, $product, $page = null)
    {
        return $this->request(sprintf('products/%s/%s/references?page=%d', $language, $this->transform($product), $page), 'GET');
    }

}
