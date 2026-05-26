import 'package:flutter/material.dart';
import '../../../data/models/incident_model.dart';

class IncidentTimeline extends StatelessWidget {
  final List<IncidentModel> incidents;

  const IncidentTimeline({super.key, required this.incidents});

  @override
  Widget build(BuildContext context) {
    return ListView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: incidents.length,
      itemBuilder: (context, index) {
        final incident = incidents[index];
        return ListTile(
          leading: CircleAvatar(
            backgroundColor: _getSeverityColor(incident.severity ?? 'medium'),
            child: const Icon(Icons.warning, color: Colors.white, size: 20),
          ),
          title: Text(incident.title ?? 'Unknown', style: const TextStyle(fontWeight: FontWeight.bold)),
          subtitle: Text(incident.incidentCode ?? ''),
          trailing: Text(
            incident.detectedAt != null
                ? _formatTime(incident.detectedAt!)
                : '',
            style: const TextStyle(fontSize: 12, color: Colors.grey),
          ),
        );
      },
    );
  }

  Color _getSeverityColor(String severity) {
    switch (severity.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      default: return Colors.blue;
    }
  }

  String _formatTime(DateTime date) {
    final now = DateTime.now();
    final diff = now.difference(date);
    if (diff.inHours < 24) return '${diff.inHours}h ago';
    return '${diff.inDays}d ago';
  }
}