<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 0 100 100"
    preserveAspectRatio="xMidYMid"
    width="100%"
  >
    <circle
      cx="50"
      cy="50"
      r="40"
      fill="none"
      stroke="#e90c59"
      stroke-width="8"
    >
      <animate
        attributeName="r"
        repeatCount="indefinite"
        dur="1s"
        values="0;40"
        keyTimes="0;1"
        keySplines="0 0.2 0.8 1"
        calcMode="spline"
        begin="-0.5s"
      />
      <animate
        attributeName="opacity"
        repeatCount="indefinite"
        dur="1s"
        values="1;0"
        keyTimes="0;1"
        keySplines="0.2 0 0.8 1"
        calcMode="spline"
        begin="-0.5s"
      />
    </circle>
    <circle
      cx="50"
      cy="50"
      r="25.295"
      fill="none"
      stroke="#ad52f9"
      stroke-width="8"
    >
      <animate
        attributeName="r"
        repeatCount="indefinite"
        dur="1s"
        values="0;40"
        keyTimes="0;1"
        keySplines="0 0.2 0.8 1"
        calcMode="spline"
      />
      <animate
        attributeName="opacity"
        repeatCount="indefinite"
        dur="1s"
        values="1;0"
        keyTimes="0;1"
        keySplines="0.2 0 0.8 1"
        calcMode="spline"
      />
    </circle>
  </svg>
</template>

<script>
export default {
  name: 'SvgRipple',
}
</script>
