<template>
  <div id="main">
    <nav>
      <a href="/">Weather Impact Logger</a>
    </nav>
    <router-view />
  </div>
</template>

<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: sans-serif; background: #f5f5f5; color: #333; }
nav { background: #1a1a2e; color: white; padding: 16px 24px; font-size: 18px; font-weight: bold; }
nav a { color: white; text-decoration: none; }
#main { max-width: 900px; margin: 0 auto; padding: 24px; }
</style>