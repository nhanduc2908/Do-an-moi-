import 'package:flutter/material.dart';
import '../../../data/models/incident_model.dart';

class IncidentCard extends StatelessWidget {
  final IncidentModel incident;

  const IncidentCard({super.key, required this.incident});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        leading: CircleAvatar(
          backgroundColor: _getSeverityColor(incident.severity ?? 'medium'),
          child: const Icon(Icons.warning, color: Colors.white),
        ),
        title: Text(incident.title ?? 'Unknown', style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text('${incident.incidentCode} • ${incident.status}'),
        trailing: Container(
          padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
          decoration: BoxDecoration(
            color: _getSeverityColor(incident.severity ?? 'medium').withOpacity(0.2),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Text(
            incident.severity ?? 'medium',
            style: TextStyle(color: _getSeverityColor(incident.severity ?? 'medium'), fontSize: 12),
          ),
        ),
        onTap: () {},
      ),
    );
  }

  Color _getSeverityColor(String severity) {
    switch (severity.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow.shade700;
      default: return Colors.green;
    }
  }
}