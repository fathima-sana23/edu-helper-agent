# EduHelperAgent 🤖

EduHelperAgent is a simple educational chatbot built with **Laravel 10** as part of a **beginner-level machine test**.  
It helps school students learn basic concepts through a friendly chat interface.

---

## 🎯 Task Objective

To build a small educational chat agent using Laravel and **LarAgent-style architecture**, demonstrating how AI agents can be integrated into a web application.

---

## 📚 Supported Topics

The chatbot supports **only** the following topics:

- 🌍 Solar System  
- ➗ Fractions  
- 💧 Water Cycle  

If a user asks about any other topic, the agent responds with:

> **“I can only help with Solar System, Fractions, or Water Cycle for now 😊”**

---

## 🧠 Agent Features

- Polite greeting for students  
- Simple explanations suitable for school students  
- Maximum response length of **60 words**  
- Topic restriction enforced at agent level  
- Conversation history remembered per chat session  

---

## 🤖 LLM Configuration (Demo Mode)

This project uses **OpenAI** as an example Large Language Model (LLM) provider.

As allowed in the task instructions, a **placeholder OpenAI API key** is configured:

```env
OPENAI_API_KEY=sk-placeholder-key
Demo Mode Behavior
When a placeholder key is used, OpenAI requests fail intentionally.

The application handles this gracefully without crashing.

The chatbot runs in demo mode and does not return live AI-generated answers.

Replacing the placeholder with a valid OpenAI API key will enable real AI responses instantly.

🗂️ Conversation Memory
Conversation history is stored in the database and linked using a session identifier.
Previous messages are loaded and reused so the chatbot remembers earlier interactions within the same session.

🎨 UI / Blade Design
The chat interface is built using Laravel Blade

UI layout and styling ideas were inspired with the help of AI design tools (such as Claude AI)

The design focuses on simplicity, clarity, and a student-friendly experience

🛠️ Tech Stack
Laravel 10+

PHP 8.1+

MySQL

OpenAI Laravel SDK (example LLM provider)

Blade Templates

🚀 How to Run the Project
bash
Copy code
composer install
php artisan migrate
php artisan serve
Open in browser:
👉 http://localhost:8000

📝 Note on LarAgent
LarAgent is currently not available as a stable Composer package.
This project follows LarAgent-style principles by implementing:

A dedicated agent class (EduHelperAgent)

Clear system instructions for agent behavior

Controlled topic scope

Conversation memory handling

✅ Conclusion
EduHelperAgent demonstrates a beginner-friendly implementation of an educational chat agent using Laravel and AI agent concepts, while remaining stable and evaluation-safe using a placeholder LLM configuration.
