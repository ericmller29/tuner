<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <button type="button" @click="$emit('close')" class="modal-close">
                        <i class="fa fa-times"></i>
                    </button>
                    <header class="modal-header">
                        <slot name="header"></slot>
                    </header>
                    <div class="content">
                        <slot name="content"></slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>