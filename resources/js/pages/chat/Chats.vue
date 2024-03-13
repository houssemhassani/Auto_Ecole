<template>
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <!-- Sidebar with user list -->
        <div class="sidebar">
          <h3>Users</h3>
          <ul class="list-group">
            <li class="list-group-item" v-for="user in filteredUsers" :key="user.id" @click="openChat(user.id)"
              :class="{ active: selectedUser === user.id }">
              {{ user.name }}
            </li>
          </ul>
        </div>
      </div>
      <div class="col-md-8">
        <!-- Main chat area -->
        <div class="chat">
          <div v-if="selectedUser">
            <div class="conversation-window">
              <div class="conversation-header">
                Chat with {{ selectedUserName }}
                <span class="close-icon" @click="closeChat">×</span>
              </div>
              <div class="conversation-messages">
                <div class="message" v-for="(message, index) in messages" :key="index"
                  :class="{ sent: message.sender_id === currentUser.id, received: message.sender_id !== currentUser.id }"
                  @click="openMessageActions(message.id)">
                  <div class="message-content">
                    <span class="message-sender">{{ message.sender_name }}:</span>
                    <span class="message-text">{{ message.content }}</span>
                  </div>
                </div>
              </div>
              <div class="conversation-input">


                <div class="messageBox">
                  <input required="" placeholder="Message..." type="text" id="messageInput" v-model="newMessage" />
                  <button id="sendButton" @click="sendMessage">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 664 663">
                      <path fill="none"
                        d="M646.293 331.888L17.7538 17.6187L155.245 331.888M646.293 331.888L17.753 646.157L155.245 331.888M646.293 331.888L318.735 330.228L155.245 331.888">
                      </path>
                      <path stroke-linejoin="round" stroke-linecap="round" stroke-width="33.67" stroke="#6c6c6c"
                        d="M646.293 331.888L17.7538 17.6187L155.245 331.888M646.293 331.888L17.753 646.157L155.245 331.888M646.293 331.888L318.735 330.228L155.245 331.888">
                      </path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="alert alert-info" role="alert">
            Please select a user to start a conversation.
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for message actions -->
    <div class="modal" v-if="showMessageActions">
      <div class="modal-content">

        <button class="deletemessage" @click="deleteMessage">
          <svg viewBox="0 0 448 512" class="svgIcon">
            <path
              d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z">
            </path>
          </svg>
        </button>
        <button class="edit-button" @click="updateMessage">
          <svg class="edit-svgIcon" viewBox="0 0 512 512">
            <path
              d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z">
            </path>
          </svg>
        </button>
        <div @click="closeModal"
          class="flex cursor-pointer items-center justify-center text-3xl text-white caret-transparent">
          <div
            class="group relative inline-flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-2 font-medium shadow-md md:h-20 md:w-20">
            <span
              class="ease absolute z-10 flex h-full w-full translate-y-full items-center justify-center rounded-full bg-[#d1b98a] text-white duration-300 group-hover:translate-y-0"></span>
            <div
              class="absolute z-50 flex h-full w-full items-center justify-center text-[#f6f2f2] group-hover:text-white">
              <svg height="40px" width="40px" viewBox="0 0 72 72" xmlns="http://www.w3.org/2000/svg">
                <path fill="white"
                  d="M 19 15 C 17.977 15 16.951875 15.390875 16.171875 16.171875 C 14.609875 17.733875 14.609875 20.266125 16.171875 21.828125 L 30.34375 36 L 16.171875 50.171875 C 14.609875 51.733875 14.609875 54.266125 16.171875 55.828125 C 16.951875 56.608125 17.977 57 19 57 C 20.023 57 21.048125 56.609125 21.828125 55.828125 L 36 41.65625 L 50.171875 55.828125 C 51.731875 57.390125 54.267125 57.390125 55.828125 55.828125 C 57.391125 54.265125 57.391125 51.734875 55.828125 50.171875 L 41.65625 36 L 55.828125 21.828125 C 57.390125 20.266125 57.390125 17.733875 55.828125 16.171875 C 54.268125 14.610875 51.731875 14.609875 50.171875 16.171875 L 36 30.34375 L 21.828125 16.171875 C 21.048125 15.391875 20.023 15 19 15 z">
                </path>
              </svg>
            </div>
          </div>
        </div>



      </div>
    </div>
  </div>
</template>

<script>
import axios from "@/axios-config";

export default {
  name: "ChatPage",
  data() {
    return {
      users: [],
      selectedUser: null,
      selectedUserName: "",
      currentUser: {
        id: JSON.parse(localStorage.getItem("currentUser")).id,
        name: "You",
      },
      messages: [],
      newMessage: "",
      showMessageActions: false,
      selectedMessageId: null,
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter(user => user.id !== this.currentUser.id);
    }
  },
  created() {
    this.loadUsers();
  },
  methods: {
    loadUsers() {
      axios
        .get("/gestionUser/AllUsers")
        .then(response => {
          this.users = response.data.data;
        })
        .catch(error => {
          console.error("Error loading users:", error);
        });
    },
    openChat(userId) {
      this.selectedUser = userId;
      this.selectedUserName = this.users.find((user) => user.id === userId).name;
      this.loadMessages(userId);
    },
    loadMessages(userId) {
      axios
        .get(`/conversations/conversation/${userId}`)
        .then(response => {
          this.messages = response.data.messages;
        })
        .catch(error => {
          console.error("Error loading messages:", error);
        });
    },
    closeChat() {
      this.selectedUser = null;
      this.selectedUserName = "";
      this.messages = [];
    },
    sendMessage() {
      if (this.newMessage.trim() !== "") {
        axios
          .post("/conversations/send", {
            recipient_id: this.selectedUser,
            content: this.newMessage,
          })
          .then(response => {
            this.messages.push(response.data.message);
            this.newMessage = "";
          })
          .catch(error => {
            console.error("Error sending message:", error);
          });
      }
    },
    openMessageActions(messageId) {
      this.selectedMessageId = messageId;
      this.showMessageActions = true;
    },
    closeModal() {
      this.showMessageActions = false;
      this.selectedMessageId = null;
    },
    deleteMessage() {
      // Impleme-nt delete message logic here
      axios.delete(`/conversations/delete/${this.selectedMessageId}`)
        .then(response => {
          console.log("Deleting message with ID:", this.selectedMessageId);
          this.closeModal();
        });
    },
    updateMessage() {

      console.log("Updating message with ID:", this.selectedMessageId);
      this.closeModal();
    },
  },
};
</script>

<style scoped>
.sidebar {
  background-color: #f8f9fa;
  padding: 20px;
}

.chat {
  padding: 20px;
}

.messages {
  max-height: 100px;
  overflow-y: auto;
}

.message {
  margin-bottom: 5px;
  padding: 5px;
  border-radius: 5px;
}

.message.sent {
  background-color: teal;
  color: #fff;
  text-align: right;
}


.message.received {
  background-color: #f0f6bf;
  text-align: left;
  color: #000;
}

.message-content {
  display: inline-block;
}

.message-sender {
  font-weight: bold;
}

.input-group-append button {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}

.conversation-window {
  border: 1px solid #ccc;
  border-radius: 10px;
  overflow: hidden;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.conversation-header {
  background-color: #0084ff;
  /* Bleu similaire à Messenger */
  color: #fff;
  padding: 10px;
  text-align: center;
  position: relative;
}

.close-icon {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  cursor: pointer;
}

.conversation-messages {
  max-height: 200px;
  overflow-y: auto;
  padding: 10px;
}

.conversation-input {
  padding: 10px;
  border-top: 1px solid #ccc;
  display: flex;
  align-items: center;
}

.conversation-input input {
  flex: 1;
  margin-right: 10px;
}

.modal {
  position: relative;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 50%;
  max-width: 200px;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: rgba(0, 0, 0, 0.3);
  padding: 10px;
  border-radius: 10px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
}

button {
  font-family: inherit;
  font-size: 20px;
  background: teal;
  color: white;
  padding: 0.7em 1em;
  padding-left: 0.9em;
  display: flex;
  align-items: center;
  border: none;
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.2s;
  cursor: pointer;
}

button span {
  display: block;
  margin-left: 0.3em;
  transition: all 0.3s ease-in-out;
}

button svg {
  display: block;
  transform-origin: center center;
  transition: transform 0.3s ease-in-out;
}

button:hover .svg-wrapper {
  animation: fly-1 0.6s ease-in-out infinite alternate;
}

button:hover svg {
  transform: translateX(1.2em) rotate(45deg) scale(1.1);
}

button:hover span {
  transform: translateX(5em);
}

button:active {
  transform: scale(0.95);
}

@keyframes fly-1 {
  from {
    transform: translateY(0.1em);
  }

  to {
    transform: translateY(-0.1em);
  }
}

.deletemessage {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: linear-gradient(to bottom right,
      rgb(69, 120, 255),
      rgb(255, 69, 69));
  border: none;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
  cursor: pointer;
  transition: transform 0.3s ease-in-out, color 0.3s ease-in-out;
  overflow: hidden;
  position: relative;
}

.svgIcon {
  width: 12px;
  transition: transform 0.3s ease-in-out;
}

.svgIcon path {
  fill: white;
}

.deletemessage:hover {
  width: 140px;
  border-radius: 50px;
  transform: scale(1.1);
  color: rgba(255, 255, 255, 0.9);
  box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.3);
}

.button:hover .svgIcon {
  width: 50px;
  transform: translateY(60%);
}

.button::before {
  position: absolute;
  top: -20px;
  content: "Delete";
  color: white;
  transition: font-size 0.3s ease-in-out, opacity 0.3s ease-in-out,
    transform 0.3s ease-in-out, color 0.3s ease-in-out;
  font-size: 2px;
}

.deletemessage:hover::before {
  font-size: 13px;
  opacity: 1;
  transform: translateY(30px);
  color: rgba(255, 255, 255, 0.9);
}

.edit-button {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: rgb(20, 20, 20);
  border: none;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
  cursor: pointer;
  transition-duration: 0.3s;
  overflow: hidden;
  position: relative;
  text-decoration: none !important;
}

.edit-svgIcon {
  width: 19px;
  transition-duration: 0.3s;
}

.edit-svgIcon path {
  fill: white;
}

.edit-button:hover {
  width: 120px;
  border-radius: 50px;
  transition-duration: 0.3s;
  background-color: rgb(255, 69, 69);
  align-items: center;
}

.edit-button:hover .edit-svgIcon {
  width: 20px;
  transition-duration: 0.3s;
  transform: translateY(60%);
  -webkit-transform: rotate(360deg);
  -moz-transform: rotate(360deg);
  -o-transform: rotate(360deg);
  -ms-transform: rotate(360deg);
  transform: rotate(360deg);
}

.edit-button::before {
  display: none;
  content: "Edit";
  color: white;
  transition-duration: 0.3s;
  font-size: 2px;
}

.edit-button:hover::before {
  display: block;
  padding-right: 10px;
  font-size: 13px;
  opacity: 1;
  transform: translateY(0px);
  transition-duration: 0.3s;
}

/*    */
.messageBox {
  width: fit-content;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f7f3f3;
  padding: 0 15px;
  border-radius: 10px;
  border: 1px solid rgb(29, 29, 29);
}

.messageBox:focus-within {
  border: 1px solid rgb(19, 18, 18);
}

.fileUploadWrapper {
  width: fit-content;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: Arial, Helvetica, sans-serif;
}

#file {
  display: none;
}

.fileUploadWrapper label {
  cursor: pointer;
  width: fit-content;
  height: fit-content;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.fileUploadWrapper label svg {
  height: 18px;
}

.fileUploadWrapper label svg path {
  transition: all 0.3s;
}

.fileUploadWrapper label svg circle {
  transition: all 0.3s;
}





.tooltip {
  position: absolute;
  top: -40px;
  display: none;
  opacity: 0;
  color: rgb(14, 13, 13);
  font-size: 10px;
  text-wrap: nowrap;
  background-color: white;
  padding: 6px 10px;
  border: 1px solid #0a0a0a;
  border-radius: 5px;
  box-shadow: 0px 5px 10px rgba(224, 221, 221, 0.596);
  transition: all 0.3s;
}

#messageInput {
  width: 200px;
  height: 100%;
  background-color: transparent;
  outline: none;
  border: none;
  padding-left: 10px;
  color: rgb(15, 15, 15);
}

#messageInput:focus~#sendButton svg path,
#messageInput:valid~#sendButton svg path {
  fill: #272727;
  stroke: rgb(5, 5, 5);
}

#sendButton {
  width: fit-content;
  height: 100%;
  background-color: transparent;
  outline: none;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
}

#sendButton svg {
  height: 18px;
  transition: all 0.3s;
}

#sendButton svg path {
  transition: all 0.3s;
}

#sendButton:hover svg path {
  fill: #f1f0f0;
  stroke: white;
}
</style>
