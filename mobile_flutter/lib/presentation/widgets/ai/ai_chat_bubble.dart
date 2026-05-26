import 'package:flutter/material.dart';

class AIChatBubble extends StatelessWidget {
  final String text;
  final bool isUser;
  final String time;
  final bool isTyping;

  const AIChatBubble({
    super.key,
    required this.text,
    required this.isUser,
    required this.time,
    this.isTyping = false,
  });

  @override
  Widget build(BuildContext context) {
    return Align(
      alignment: isUser ? Alignment.centerRight : Alignment.centerLeft,
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.all(12),
        constraints: BoxConstraints(
          maxWidth: MediaQuery.of(context).size.width * 0.75,
        ),
        decoration: BoxDecoration(
          color: isUser ? Colors.blue : Colors.grey.shade200,
          borderRadius: BorderRadius.only(
            topLeft: const Radius.circular(12),
            topRight: const Radius.circular(12),
            bottomLeft: Radius.circular(isUser ? 12 : 0),
            bottomRight: Radius.circular(isUser ? 0 : 12),
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (isTyping)
              const SizedBox(
                width: 40,
                child: Row(
                  children: [
                    DotAnimation(delay: Duration(milliseconds: 0)),
                    DotAnimation(delay: Duration(milliseconds: 300)),
                    DotAnimation(delay: Duration(milliseconds: 600)),
                  ],
                ),
              )
            else
              Text(
                text,
                style: TextStyle(color: isUser ? Colors.white : Colors.black87),
              ),
            const SizedBox(height: 4),
            Text(
              time,
              style: TextStyle(
                fontSize: 10,
                color: isUser ? Colors.white70 : Colors.grey,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class DotAnimation extends StatefulWidget {
  final Duration delay;

  const DotAnimation({super.key, required this.delay});

  @override
  State<DotAnimation> createState() => _DotAnimationState();
}

class _DotAnimationState extends State<DotAnimation> with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      duration: const Duration(milliseconds: 600),
      vsync: this,
    );
    _animation = Tween<double>(begin: 0.3, end: 1.0).animate(_controller);
    _controller.repeat(reverse: true);
    Future.delayed(widget.delay, () => _controller.forward());
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return FadeTransition(
      opacity: _animation,
      child: const Text('.', style: TextStyle(fontSize: 20)),
    );
  }
}