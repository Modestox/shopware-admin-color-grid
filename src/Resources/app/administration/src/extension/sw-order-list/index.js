/**
 * Modestox - Shopware Admin Color Grid
 *
 * @copyright Copyright (c) 2026 Sergey Kuzmitsky
 * @license   MIT
 * @link      https://github.com/Modestox/shopware-admin-color-grid
 */

import './sw-order-list.scss';

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.override('sw-order-list', {
    inject: ['repositoryFactory'],

    data() {
        return {
            colorGridRules: [],
        };
    },

    computed: {
        colorGridRepository() {
            return this.repositoryFactory.create('modestox_admin_color_grid');
        },
    },

    created() {
        this.loadColorGridRules();
    },

    watch: {
        isLoading(newVal) {
            if (!newVal) {
                this.applyRowColors();
            }
        },
    },

    updated() {
        this.applyRowColors();
    },

    methods: {
        async loadColorGridRules() {
            try {
                const criteria = new Criteria();
                criteria.addFilter(Criteria.equals('active', true));

                const result = await this.colorGridRepository.search(criteria, Shopware.Context.api);
                this.colorGridRules = result || [];
                this.applyRowColors();
            } catch (error) {
                console.error('[ModestoxColorGrid] Failed to fetch active color rules:', error);
            }
        },

        applyRowColors() {
            if (!this.colorGridRules || this.colorGridRules.length === 0) {
                this.resetRowColors();
                return;
            }

            this.$nextTick(() => {
                if (!this.$el?.querySelectorAll || !this.orders || this.orders.length === 0) {
                    return;
                }

                const rows = this.$el.querySelectorAll('.sw-data-grid__body .sw-data-grid__row');
                const ruleMap = new Map(this.colorGridRules.map((r) => [r.stateMachineStateId, r.color]));

                rows.forEach((row, index) => {
                    const order = this.orders[index];
                    if (!order) {
                        return;
                    }

                    const stateId = order.stateMachineStateId
                        || order.stateId
                        || (order.stateMachineState && order.stateMachineState.id);

                    const color = stateId ? ruleMap.get(stateId) : null;

                    if (color) {
                        row.style.setProperty('--modestox-row-bg-color', color);
                        row.classList.add('has--modestox-color');
                    } else {
                        row.style.removeProperty('--modestox-row-bg-color');
                        row.classList.remove('has--modestox-color');
                    }
                });
            });
        },

        resetRowColors() {
            this.$nextTick(() => {
                if (!this.$el?.querySelectorAll) {
                    return;
                }

                const rows = this.$el.querySelectorAll('.sw-data-grid__body .sw-data-grid__row');
                rows.forEach((row) => {
                    row.style.removeProperty('--modestox-row-bg-color');
                    row.classList.remove('has--modestox-color');
                });
            });
        },
    },
});