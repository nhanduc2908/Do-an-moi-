// Đường dẫn: mobile_flutter/lib/presentation/widgets/ai/ai_loading_animation.dart

import 'package:flutter/material.dart';

class AILoadingAnimation extends StatelessWidget {
  final String message;

  const AILoadingAnimation({super.key, this.message = 'AI is thinking...'});

  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Container(
            width: 60,
            height: 60,
            decoration: BoxDecoration(
              color: Colors.blue.shade50,
              borderRadius: BorderRadius.circular(12),
            ),
            child: const Center(
              child: SizedBox(
                width: 30,
                height: 30,
                child: CircularProgressIndicator(
                  strokeWidth: 2,
                  color: Colors.blue,
                ),
              ),
            ),
          ),
          const SizedBox(height: 16),
          Text(
            message,
            style: const TextStyle(color: Colors.grey),
          ),
        ],
      ),
    );
  }
}