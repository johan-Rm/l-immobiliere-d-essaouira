<template>
    <aside v-if="isDemo" class="demo-notice" role="status">
        {{ $store.state.i18n.currentLocale === 'en' ? 'Demonstration — fictitious data. Messages are not sent.' : 'Démonstration — données fictives. Aucun message n’est envoyé.' }}
    </aside>
</template>
<script>
export default {
    computed: { isDemo() { return process.env.DEMO_MODE === 'true' } }
}
</script>
<style scoped>
.demo-notice { margin-top: 90px; padding: 12px 24px; background: #edf4ef; color: #244839; text-align: center; font-size: 14px; }
</style>
