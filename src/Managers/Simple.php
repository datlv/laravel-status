<?php namespace Datlv\Status\Managers;

use Datlv\User\User;
use Datlv\User\Support\HasOwner;
use Authority;

/**
 * Class Simple
 * Status Manager đơn giãn: 2 trạng thái (đang biên tập, đã xuất bản)
 *
 * @package Datlv\Status\Managers
 */
class Simple extends NewStatusManager {
    /**
     * @return string
     */
    public function defaultStatus() {
        return 'editing';
    }

    /**
     *
     * @return array
     */
    protected function allStatuses() {
        return [
            [
                'value'   => 'editing',
                'actions' => [
                    'read|update|delete' => function ( $model, $user ) {
                        /** @var HasOwner $model */
                        return $user && ( Authority::user( $user )->isAdmin() || ( $model && $model->isOwnedBy( $user ) ) );
                    },
                ],
                'up'      => 'published',
                'css'     => 'default',
            ],
            [
                'value'   => 'published',
                'actions' => [
                    'read'          => true,
                    'update|delete' => function ( $model, $user ) {
                        /** @var HasOwner $model */
                        return $user && ( Authority::user( $user )->isAdmin() || ( $model && $model->isOwnedBy( $user ) ) );
                    },
                ],
                'down'    => 'editing',
                'css'     => 'success',
            ],
        ];
    }
}