<template>
    <panel-item :field="field">
        <template slot="value">
            <div v-if="field" class="flex flex-col justify-start w-full">
                <div class="flex">
                    <div  class="mr-3">Couleurs: </div>
                    <div v-for="(value,key) in field.selectedColors" :key="key" class="flex mb-2">
                        <div class="mx-2" :style="'background-color: '+ value.code" style="border-radius: 15px;width: 20px;height: 20px"></div>
                    </div>
                </div>

                <div class="flex">
                    <div  class="mr-3">Tailles: </div>
                    <div v-for="(value,key) in field.selectedSizes" :key="key" class="flex mb-2">
                        <div class="mx-2">{{ value.label}}</div>
                    </div>
                </div>
            </div>
            <div v-else>
                Aucun données !?
            </div>
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    mounted() {
        console.log(this.field)
    }
}
</script>
