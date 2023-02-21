const xmlhttp = new XMLHttpRequest();
xmlhttp.onload = function() {
  const topics = JSON.parse(this.responseText);
  let HTMLlist = ''
  topics.forEach(topic => {
    HTMLlist += '<li>หัวข้อกระทู้ : '+topic.title + "<br>เนื้อหากระทู้ : " + topic.content+'<hr></li>';
  })
  document.getElementById("topicCount").textContent = topics.length
  document.getElementById("list").innerHTML = HTMLlist
}
xmlhttp.open("GET", "topics.json");
xmlhttp.send();