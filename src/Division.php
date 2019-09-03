<?php

namespace ChinaDivisions;

class Division
{
    /**
     * 区划 ID
     *
     * @var string|int
     */
    protected $divisionId;

    /**
     * 自身信息
     *
     * @var array
     */
    protected $data;

    /**
     * 子区划信息
     *
     * @var array
     */
    protected $children;

    /**
     * 客户端
     *
     * @var Client
     */
    protected $client;

    public function __construct($divisionId, Client $client = null)
    {
        $this->divisionId = $divisionId;
        $this->client = $client ?? new Client();
    }

    /**
     * 子区划信息
     *
     * @return static[]
     */
    public function children()
    {
        if ($this->needToRefreshChildren()) {
            $this->refreshChildren();
        }

        return $this->children;
    }

    /**
     * 需要刷新子区划信息吗？
     *
     * @return bool
     */
    protected function needToRefreshChildren()
    {
        return $this->children === null;
    }

    /**
     * 刷新子区划信息
     *
     * @return void
     */
    protected function refreshChildren()
    {
        $request = $this->client->build(
            'CNDZK_CHINA_SUB_DIVISIONS',
            'cndzk_sub_divisions_cpcode',
            ['divisionId' => $this->divisionId],
            'cndzk_address_sub_divisions'
        );

        $data = $this->client->send($request);

        $list = [];

        foreach ($data['divisionsList'] as $item) {
            $division = new self($item['divisionId'], $this->client);
            $division->data = $item;

            $list[] = $division;
        }

        return $this->children = $list;
    }

    /**
     * 获取自身信息
     *
     * @return array
     */
    public function self()
    {
        if ($this->needToRefreshSelf()) {
            $this->refreshSelf();
        }

        return $this->data;
    }

    /**
     * 携带子区划的自身信息
     *
     * @return array
     */
    public function selfWithChildren()
    {
        $data = $this->data;
        $data['children'] = $this->children();

        return $data;
    }

    /**
     * 需要刷新自身信息吗？
     *
     * @return bool
     */
    protected function needToRefreshSelf()
    {
        return $this->data === null;
    }

    /**
     * 刷新自身信息
     *
     * @return void
     */
    protected function refreshSelf()
    {
        $request = $this->client->build(
            'CNDZK_CHINA_DIVISION',
            'cndzk_common_division_cpcode',
            ['divisionId' => $this->divisionId],
            'cndzk_address_common_division'
        );

        $data = $this->client->send($request);

        $this->data = $data['chinaDivisionVO'];
    }

    /**
     * 获取可读的省市区地址（例：山东省青岛市市北区）
     *
     * @return string
     */
    public function breadcrumb()
    {
        $address = '';

        foreach ($this->ancestors() as $ancestor) {
            $address .= $ancestor->self()['divisionName'];
        }

        return $address;
    }

    /**
     * 获取上一级父区划
     *
     * @return static
     */
    public function parent()
    {
        $self = $this->self();

        return new self($self['parentId'], $this->client);
    }

    /**
     * 获取所有上级父区划
     *
     * @see self::breadcrumb()
     *
     * @return static[]
     */
    public function ancestors()
    {
        $func = function (self $item) use (&$func) {
            $self = $item->self();
            if ($self['parentId'] == 0) {
                return [];
            }
            $ancestors = $func($item->parent());
            $ancestors[] = $item;

            return $ancestors;
        };

        return $func($this);
    }

    /**
     * 地址录入联想
     *
     * @param string $detailAddress
     * @param string $divisionAddress
     * @param int    $limit
     * @param bool   $isDegrade
     *
     * @return array
     */
    public function guess($detailAddress, $divisionAddress = '', $limit = 10, $isDegrade = false)
    {
        $request = $this->client->build(
            'CNDZK_GUESS_ADDRESS',
            'cndzk_guess_address_cpcode',
            [
                'divisionAddress' => $divisionAddress ?: $this->breadcrumb(),
                'detailAddress'   => $detailAddress,
                'isDegrade'       => $isDegrade ? 1 : 0,
                'limit'           => intval($limit),
            ],
            'cndzk_guess_address'
        );

        $data = $this->client->send($request);

        return $data['guess_address_response']['data'];
    }

    /**
     * 地址规范查询
     *
     * @return array
     *
     * @deprecated v0.1.2
     */
    public function search($fullAddress, $limit = 20)
    {
        $request = $this->client->build(
            'CNDZK_ADDRESS_QUERY',
            'cndzk_address_query_cpcode',
            [
                'address' => $fullAddress,
                'limit'   => intval($limit),
            ],
            'cndzk_address_query'
        );

        $data = $this->client->send($request);

        return $data['AddressBuildings'];
    }
}
