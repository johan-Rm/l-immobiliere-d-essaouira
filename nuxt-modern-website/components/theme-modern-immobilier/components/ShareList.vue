<template>
    <div class="share-list wt-box" :class="params.class">
        <div class="row p-lr15">
            <h4 class="tagcloud text-uppercase"> {{ $t(params.label) }} : </h4>
            <div class="widget_social_inks">
                <client-only>
                <social-sharing 
                  :url="data.url"
                  :title="data.title"
                  :description="data.description"
                  :quote="data.description"
                  :hashtags="data.title"
                  :twitter-user="data.url"
                  inline-template
                >
                <div class="social-icons social-md social-square social-dark">
                    <network network="facebook">
                        <li>
                          <a class="fab fa-facebook"></a>
                        </li>
                    </network>
                    <!-- <network network="googleplus">
                      <li>
                        <a class="fab fa-google-plus"></a>
                      </li>  
                    </network> -->
                   <!--  <network network="line">
                        <a class="fab fa-line"></a>
                    </network> -->
                    <network network="linkedin">
                      <li>
                        <a class="fab fa-linkedin"></a>
                      </li>  
                    </network>
                   <!--  <network network="odnoklassniki">
                      <li>
                        <a class="fab fa-odnoklassniki"></a>
                      </li>  
                    </network> -->
                    <network network="pinterest">
                      <li>
                        <a class="fab fa-pinterest"></a>
                      </li>  
                    </network>
                   <!--  <network network="reddit">
                      <li>
                        <a class="fab fa-reddit"></a>
                      </li>  
                    </network>
                    <network network="skype">
                      <li>
                        <a class="fab fa-skype"></a>
                      </li>  
                    </network>
                    <network network="sms">
                      <li>
                        <a class="fas fa-comment-dots"></a>
                      </li>  
                    </network> -->
                    <network network="telegram">
                      <li>
                        <a class="fab fa-telegram"></a>
                      </li>  
                    </network>
                   <!--  <network network="twitter">
                      <li>
                        <a class="fab fa-twitter"></a>
                      </li>  
                    </network> -->
                    <network network="whatsapp">
                      <li>
                        <a class="fab fa-whatsapp"></a>
                      </li>  
                    </network>
                    <network network="email">
                      <li>
                        <a class="fas fa-envelope"></a>
                      </li>  
                    </network>
                    <!-- <network network="weibo">
                      <li>
                        <a class="fab fa-weibo"></a>
                      </li>  
                    </network>
                    <network network="vk">
                      <li>
                        <a class="fab fa-vk"></a>
                      </li>
                    </network> -->
                </div>
              </social-sharing>
              </client-only>
            </div>
        </div>
    </div>
</template> 

<script>
export default {
    name: 'ShareList',
   props: {
        data: {
            type: Object
        },
        params: {
            type: Object
        }
    },
    computed:{
        classname(string) {
            return string.toLowerCase()        
        }
    }
}
</script>
<style lang="scss">


.share-list .social-icons.social-square li {
      margin: 0 4px 0 0 ;
}

.share-list .social-icons.social-square.social-dark a
, .share-list .social-icons.social-radius.social-dark a 
{
  background-color: var(--color-primary);
  margin: 2px;
  color: #fff;
  border: none;
  cursor: pointer;
}

.share-list .social-icons.social-square.social-dark a:hover
, .share-list .social-icons.social-radius.social-dark a:hover 
{
  background-color: var(--color-secondary);
}

.social-icons.social-square.social-dark a:hover
, .social-icons.social-radius.social-dark a:hover 
{
    background-color: #000;
}

.share-list a {
  text-align: center
}

</style>