// Đường dẫn: mobile_flutter/lib/presentation/widgets/assessment/assessment_timeline.dart

import 'package:flutter/material.dart';

class AssessmentTimeline extends StatelessWidget {
  final List<TimelineEvent> events;

  const AssessmentTimeline({super.key, required this.events});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: events.length,
      itemBuilder: (context, index) {
        final event = events[index];
        final isLast = index == events.length - 1;
        return Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SizedBox(
              width: 40,
              child: Column(
                children: [
                  Container(
                    width: 12,
                    height: 12,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: _getEventColor(event.type),
                    ),
                  ),
                  if (!isLast)
                    Container(
                      width: 2,
                      height: 60,
                      color: Colors.grey.shade300,
                    ),
                ],
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.only(bottom: 16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      event.title,
                      style: const TextStyle(fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      event.description,
                      style: const TextStyle(fontSize: 12, color: Colors.grey),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      _formatTime(event.timestamp),
                      style: const TextStyle(fontSize: 10, color: Colors.grey),
                    ),
                  ],
                ),
              ),
            ),
          ],
        );
      },
    );
  }

  Color _getEventColor(String type) {
    switch (type) {
      case 'creation': return Colors.blue;
      case 'submission': return Colors.green;
      case 'review': return Colors.orange;
      case 'completion': return Colors.purple;
      default: return Colors.grey;
    }
  }

  String _formatTime(DateTime date) {
    return '${date.day}/${date.month}/${date.year} ${date.hour}:${date.minute.toString().padLeft(2, '0')}';
  }
}

class TimelineEvent {
  final String title;
  final String description;
  final DateTime timestamp;
  final String type;

  TimelineEvent({
    required this.title,
    required this.description,
    required this.timestamp,
    required this.type,
  });
}