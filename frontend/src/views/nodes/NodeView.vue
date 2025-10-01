<script setup>
import { nodeApi } from '@/api/nodeApi'
import * as vis from 'vis-network/dist/vis-network'
import { onMounted, ref, useTemplateRef } from 'vue'

const listNode = ref([])

const fetchNodes = async () => {
    const res = await nodeApi.listNode()
    const resData = res.data

    console.log(resData);
    
}

fetchNodes()

// var nodes = new vis.DataSet([
//     { id: 1, label: "Node 1" },
//     { id: 2, label: "Node 2" },
//     { id: 3, label: "Node 3" },
//     { id: 4, label: "Node 4" },
//     { id: 5, label: "Node 5" },
// ])

// var edges = new vis.DataSet([
//     { from: 1, to: 3 },
//     { from: 1, to: 2 },
//     { from: 2, to: 4 },
//     { from: 2, to: 5 },
//     { from: 3, to: 3 },
// ])

// var data = {
//     nodes: nodes,
//     edges: edges,
// }

// var options = {}
// const container = useTemplateRef('networkContainer')


// onMounted(() => {
//     new vis.Network(container.value, data, options)
// })

</script>

<template>
    <div>
        <div>
            
        </div>
        <div ref="networkContainer" class="w-screen h-screen"></div>
    </div>
</template>
