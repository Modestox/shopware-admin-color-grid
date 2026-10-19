/**
 * Modestox - Shopware Admin Color Grid
 *
 * @copyright Copyright (c) 2026 Sergey Kuzmitsky
 * @license   MIT
 * @link      https://github.com/Modestox/shopware-admin-color-grid
 */

import template from './modestox-color-grid-index.html.twig';
import './modestox-color-grid-index.scss';

const {Component, Mixin} = Shopware;
const {Criteria} = Shopware.Data;

Component.register('modestox-color-grid-index', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('notification'),
    ],

    data() {
        return {
            rules: null,
            isLoading: false,
            showModal: false,
            currentRule: null,
        };
    },

    computed: {
        colorGridRepository() {
            return this.repositoryFactory.create('modestox_admin_color_grid');
        },

        stateMachineStateCriteria() {
            const criteria = new Criteria(1, 50);
            criteria.addAssociation('stateMachine');
            criteria.addFilter(
                Criteria.equals('stateMachine.technicalName', 'order.state')
            );
            return criteria;
        },

        columns() {
            return [
                {
                    property: 'stateMachineState.translated.name',
                    label: this.$tc('modestox-color-grid.list.columnStatus'),
                    rawData: true,
                },
                {
                    property: 'color',
                    label: this.$tc('modestox-color-grid.list.columnColor'),
                },
                {
                    property: 'active',
                    label: this.$tc('modestox-color-grid.list.columnActive'),
                },
            ];
        },
    },

    created() {
        this.loadRules();
    },

    methods: {
        async loadRules() {
            this.isLoading = true;

            const criteria = new Criteria();
            criteria.addAssociation('stateMachineState');

            try {
                this.rules = await this.colorGridRepository.search(criteria, Shopware.Context.api);
            } catch (error) {
                this.createNotificationError({
                    message: this.$tc('modestox-color-grid.notification.loadError'),
                });
            } finally {
                this.isLoading = false;
            }
        },

        onAddNewRule() {
            const rule = this.colorGridRepository.create(Shopware.Context.api);
            rule.entityType = 'order';
            rule.stateMachineStateId = null;
            rule.color = '#3498db';
            rule.active = true;

            this.currentRule = rule;
            this.showModal = true;
        },

        onEditRule(rule) {
            this.currentRule = rule;
            this.showModal = true;
        },

        onCloseModal() {
            this.showModal = false;
            this.currentRule = null;
            this.loadRules();
        },

        async onSaveRule() {
            if (!this.currentRule || !this.currentRule.stateMachineStateId) {
                this.createNotificationError({
                    message: this.$tc('modestox-color-grid.notification.selectStatusError'),
                });
                return;
            }

            this.isLoading = true;

            try {
                await this.colorGridRepository.save(this.currentRule, Shopware.Context.api);
                this.createNotificationSuccess({
                    message: this.$tc('modestox-color-grid.notification.saveSuccess'),
                });
                this.showModal = false;
                this.currentRule = null;
                await this.loadRules();
            } catch (error) {
                const firstError = error?.response?.data?.errors?.[0];
                const detail = firstError?.detail;

                const errorMessage = (detail && this.$te(detail))
                    ? this.$tc(detail)
                    : this.$tc('modestox-color-grid.notification.saveError');

                this.createNotificationError({
                    message: errorMessage,
                });
            } finally {
                this.isLoading = false;
            }
        },

        async onDeleteRule(rule) {
            this.isLoading = true;

            try {
                await this.colorGridRepository.delete(rule.id, Shopware.Context.api);
                this.createNotificationSuccess({
                    message: this.$tc('modestox-color-grid.notification.deleteSuccess'),
                });
                await this.loadRules();
            } catch (error) {
                this.createNotificationError({
                    message: this.$tc('modestox-color-grid.notification.deleteError'),
                });
            } finally {
                this.isLoading = false;
            }
        },

        async onInlineActiveChange(rule) {
            this.isLoading = true;

            try {
                await this.colorGridRepository.save(rule, Shopware.Context.api);
                await this.loadRules();
            } catch (error) {
                rule.active = !rule.active;

                this.createNotificationError({
                    message: this.$tc('modestox-color-grid.notification.saveError'),
                });
            } finally {
                this.isLoading = false;
            }
        },
    },
});