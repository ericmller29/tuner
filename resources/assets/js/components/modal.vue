<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
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