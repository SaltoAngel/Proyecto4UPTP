<script>
// Helpers globales para Skeleton Loading
(function(){
  function makeSkeleton(id, opts) {
    var el = document.getElementById(id);
    if (!el) return null;
    var parent = el.parentElement;
    var skId = 'sk-' + id;
    if (document.getElementById(skId)) return document.getElementById(skId);
    var skeleton = document.createElement('div');
    skeleton.id = skId;
    skeleton.className = 'skeleton ' + (opts.type === 'text' ? 'skeleton-text' : 'skeleton-rect');
    if (opts.height) skeleton.style.height = opts.height + 'px';
    if (opts.width) skeleton.style.width = opts.width + 'px';
    parent.insertBefore(skeleton, el);
    return skeleton;
  }
  window.skeletonAttach = function(id, opts){
    opts = opts || {};
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.add('d-none');
    makeSkeleton(id, opts);
  };
  window.skeletonReady = function(id){
    var el = document.getElementById(id);
    var sk = document.getElementById('sk-' + id);
    if (sk) sk.classList.add('d-none');
    if (el) el.classList.remove('d-none');
  };
})();
</script>
