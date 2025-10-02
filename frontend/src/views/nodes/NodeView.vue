<script setup>
import { nodeApi } from '@/api/nodeApi'
import * as vis from 'vis-network/dist/vis-network'
import { computed, onMounted, ref, useTemplateRef } from 'vue'

const listNode = ref([])
const listEdge = ref([])
const networkContainerRef = useTemplateRef('networkContainer')
const nodeDataSets = computed(() => {
    return new vis.DataSet(listNode.value.map((item) => ({
        id: item.id,
        label: item.label,
    })))
})

const edgeDataSets = computed(() => {
    return new vis.DataSet(listEdge.value.map((item) => ({
        from: item.from,
        to: item.to,
    })))
})

const fetchNodes = async () => {
    try {
        const res = await nodeApi.listNode()
        const resData = res.data

        listNode.value = resData.data.items
    } catch (error) {
        console.error(error)
        alert('Failed to fetch nodes')
    }
}

onMounted(async () => {
    await fetchNodes()

    new vis.Network(networkContainerRef.value, {
        nodes: nodeDataSets.value,
        edges: edgeDataSets.value,
    }, {})
})

</script>

<template>
    <div>
        <div>
            
        </div>
        <div ref="networkContainer" class="w-screen h-screen"></div>
    </div>
</template>
