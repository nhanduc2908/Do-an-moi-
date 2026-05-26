// Đường dẫn: mobile_flutter/lib/presentation/widgets/report/report_schedule_picker.dart

import 'package:flutter/material.dart';

class ReportSchedulePicker extends StatefulWidget {
  final Function(Map<String, dynamic>) onScheduleSet;

  const ReportSchedulePicker({super.key, required this.onScheduleSet});

  @override
  State<ReportSchedulePicker> createState() => _ReportSchedulePickerState();
}

class _ReportSchedulePickerState extends State<ReportSchedulePicker> {
  String _frequency = 'weekly';
  TimeOfDay _time = const TimeOfDay(hour: 8, minute: 0);
  List<String> _recipients = [];

  Future<void> _selectTime() async {
    final picked = await showTimePicker(context: context, initialTime: _time);
    if (picked != null) setState(() => _time = picked);
  }

  void _addRecipient(String email) {
    if (email.isNotEmpty && !_recipients.contains(email)) {
      setState(() => _recipients.add(email));
    }
  }

  void _saveSchedule() {
    widget.onScheduleSet({
      'frequency': _frequency,
      'time': '${_time.hour}:${_time.minute}',
      'recipients': _recipients,
    });
  }

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const Text('Schedule Report', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              value: _frequency,
              items: const [
                DropdownMenuItem(value: 'daily', child: Text('Daily')),
                DropdownMenuItem(value: 'weekly', child: Text('Weekly')),
                DropdownMenuItem(value: 'monthly', child: Text('Monthly')),
              ],
              onChanged: (value) => setState(() => _frequency = value!),
              decoration: const InputDecoration(labelText: 'Frequency', border: OutlineInputBorder()),
            ),
            const SizedBox(height: 12),
            InkWell(
              onTap: _selectTime,
              child: Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  border: Border.all(color: Colors.grey.shade300),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text('Time: ${_time.format(context)}'),
                    const Icon(Icons.access_time),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 12),
            Row(
              children: [
                Expanded(
                  child: TextField(
                    decoration: const InputDecoration(
                      hintText: 'Add recipient email',
                      border: OutlineInputBorder(),
                    ),
                    onSubmitted: _addRecipient,
                  ),
                ),
                const SizedBox(width: 8),
                IconButton(
                  icon: const Icon(Icons.add_circle, color: Colors.blue),
                  onPressed: () => _addRecipient(''),
                ),
              ],
            ),
            if (_recipients.isNotEmpty) ...[
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                children: _recipients.map((email) => Chip(
                  label: Text(email),
                  onDeleted: () => setState(() => _recipients.remove(email)),
                )).toList(),
              ),
            ],
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: _saveSchedule,
              child: const Text('Schedule Report'),
            ),
          ],
        ),
      ),
    );
  }
}