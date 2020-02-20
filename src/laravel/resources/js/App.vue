<template>
  <div>
    <header>
      <!-- script部でテンプレートを呼び出す -->
      <Navbar />
    </header>

    <main>
      <div class="container">
        <RouterView />
      </div>
    </main>

    <footer>
      <Footer />
    </footer>
  </div>
</template>

<script>

import Navbar from './components/Navbar'
import Footer from './components/Footer'


export default {
  components: {
    Navbar,
    Footer
  }
}
</script>
