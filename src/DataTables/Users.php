<?php

namespace KikCMS\DataTables;


use KikCMS\Classes\DataTable\DataTable;
use KikCMS\Forms\UserForm;
use KikCMS\Models\User;

class Users extends DataTable
{
    /**
     * @inheritdoc
     */
    public function getDefaultQuery()
    {
        return parent::getDefaultQuery()->columns([
            User::FIELD_ID,
            User::FIELD_EMAIL,
            User::FIELD_BLOCKED
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getModel(): string
    {
        return User::class;
    }

    /**
     * @inheritdoc
     */
    public function getFormClass(): string
    {
        return UserForm::class;
    }

    /**
     * @inheritdoc
     */
    public function getLabels(): array
    {
        return [
            $this->translator->tl('dataTables.users.singular'),
            $this->translator->tl('dataTables.users.plural')
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getTableFieldMap(): array
    {
        return [
            User::FIELD_ID      => $this->translator->tl('fields.id'),
            User::FIELD_EMAIL   => $this->translator->tl('fields.email'),
            User::FIELD_BLOCKED => $this->translator->tl('fields.blocked'),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function initialize()
    {
        $this->setFieldFormatting(User::FIELD_BLOCKED, function($value){
            return $value == 1 ? '<span style="color: #A00000;" class="glyphicon glyphicon-ban-circle"></span>' : '';
        });

        $this->addTableButton('glyphicon glyphicon-link', $this->translator->tl('dataTables.users.activationLink'), 'link');
    }
}