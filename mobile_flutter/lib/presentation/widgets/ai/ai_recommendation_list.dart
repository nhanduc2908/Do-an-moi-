// Đường dẫn: mobile_flutter/lib/presentation/widgets/ai/ai_recommendation_list.dart

import 'package:flutter/material.dart';

class AIRecommendationList extends StatelessWidget {
  final List<RecommendationItem> recommendations;

  const AIRecommendationList({super.key, required this.recommendations});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: recommendations.length,
      itemBuilder: (context, index) {
        final rec = recommendations[index];
        return Card(
          margin: const EdgeInsets.only(bottom: 12),
          child: ListTile(
            leading: CircleAvatar(
              backgroundColor: _getPriorityColor(rec.priority),
              child: const Icon(Icons.lightbulb, color: Colors.white, size: 20),
            ),
            title: Text(rec.title, style: const TextStyle(fontWeight: FontWeight.bold)),
            subtitle: Text(rec.description),
            trailing: IconButton(
              icon: const Icon(Icons.check_circle, color: Colors.green),
              onPressed: rec.onApply,
            ),
          ),
        );
      },
    );
  }

  Color _getPriorityColor(String priority) {
    switch (priority.toLowerCase()) {
      case 'high': return Colors.red;
      case 'medium': return Colors.orange;
      default: return Colors.blue;
    }
  }
}

class RecommendationItem {
  final String title;
  final String description;
  final String priority;
  final VoidCallback onApply;

  RecommendationItem({
    required this.title,
    required this.description,
    required this.priority,
    required this.onApply,
  });
}