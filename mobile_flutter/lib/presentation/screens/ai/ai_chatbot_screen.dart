import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../widgets/ai/ai_chat_bubble.dart';

class AIChatbotScreen extends ConsumerStatefulWidget {
  const AIChatbotScreen({super.key});

  @override
  ConsumerState<AIChatbotScreen> createState() => _AIChatbotScreenState();
}

class _AIChatbotScreenState extends ConsumerState<AIChatbotScreen> {
  final TextEditingController _messageController = TextEditingController();
  final List<Map<String, dynamic>> _messages = [
    {'text': 'Hello! I am your AI Security Assistant. How can I help you today?', 'isUser': false, 'time': '10:30'},
  ];
  final ScrollController _scrollController = ScrollController();

  void _sendMessage() {
    if (_messageController.text.isEmpty) return;
    
    setState(() {
      _messages.add({
        'text': _messageController.text,
        'isUser': true,
        'time': _getCurrentTime(),
      });
      _messageController.clear();
    });
    
    _scrollToBottom();
    
    // Simulate AI response
    Future.delayed(const Duration(seconds: 1), () {
      setState(() {
        _messages.add({
          'text': _getAIResponse(_messages.last['text']),
          'isUser': false,
          'time': _getCurrentTime(),
        });
      });
      _scrollToBottom();
    });
  }

  String _getAIResponse(String userMessage) {
    if (userMessage.toLowerCase().contains('scan')) {
      return 'I can help you run a security scan. What type of scan would you like to perform? (Web, Network, Vulnerability)';
    }
    if (userMessage.toLowerCase().contains('report')) {
      return 'I can generate security reports. Please specify the report type and date range.';
    }
    if (userMessage.toLowerCase().contains('incident')) {
      return 'To report an incident, please provide details about the incident including time, impact, and affected systems.';
    }
    return 'I understand your request. Let me help you with that. Could you provide more details?';
  }

  String _getCurrentTime() {
    return '${DateTime.now().hour}:${DateTime.now().minute.toString().padLeft(2, '0')}';
  }

  void _scrollToBottom() {
    Future.delayed(const Duration(milliseconds: 100), () {
      _scrollController.animateTo(
        _scrollController.position.maxScrollExtent,
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeOut,
      );
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('AI Security Assistant'),
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              controller: _scrollController,
              padding: const EdgeInsets.all(16),
              itemCount: _messages.length,
              itemBuilder: (context, index) {
                final message = _messages[index];
                return AIChatBubble(
                  text: message['text'],
                  isUser: message['isUser'],
                  time: message['time'],
                );
              },
            ),
          ),
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: Colors.white,
              boxShadow: [
                BoxShadow(
                  color: Colors.grey.shade200,
                  offset: const Offset(0, -2),
                  blurRadius: 4,
                ),
              ],
            ),
            child: Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _messageController,
                    decoration: InputDecoration(
                      hintText: 'Type your message...',
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(24),
                      ),
                      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                    ),
                    onSubmitted: (_) => _sendMessage(),
                  ),
                ),
                const SizedBox(width: 8),
                CircleAvatar(
                  backgroundColor: Colors.blue,
                  child: IconButton(
                    icon: const Icon(Icons.send, color: Colors.white),
                    onPressed: _sendMessage,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}