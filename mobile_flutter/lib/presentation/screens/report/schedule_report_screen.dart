import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../providers/report_provider.dart';
import '../../widgets/common/custom_button.dart';

class ScheduleReportScreen extends ConsumerStatefulWidget {
  const ScheduleReportScreen({super.key});

  @override
  ConsumerState<ScheduleReportScreen> createState() => _ScheduleReportScreenState();
}

class _ScheduleReportScreenState extends ConsumerState<ScheduleReportScreen> {
  String _frequency = 'weekly';
  TimeOfDay _time = const TimeOfDay(hour: 8, minute: 0);
  List<String> _recipients = [];

  Future<void> _selectTime() async {
    final picked = await showTimePicker(context: context, initialTime: _time);
    if (picked != null) setState(() => _time = picked);
  }

  Future<void> _scheduleReport() async {
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Report scheduled successfully')),
    );
    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Schedule Report'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    const Text('Schedule Settings', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    DropdownButtonFormField<String>(
                      value: _frequency,
                      items: const [
                        DropdownMenuItem(value: 'daily', child: Text('Daily')),
                        DropdownMenuItem(value: 'weekly', child: Text('Weekly')),
                        DropdownMenuItem(value: 'monthly', child: Text('Monthly')),
                      ],
                      onChanged: (value) => setState(() => _frequency = value!),
                      decoration: const InputDecoration(
                        labelText: 'Frequency',
                        border: OutlineInputBorder(),
                      ),
                    ),
                    const SizedBox(height: 16),
                    ListTile(
                      title: const Text('Time'),
                      subtitle: Text(_time.format(context)),
                      trailing: const Icon(Icons.access_time),
                      onTap: _selectTime,
                    ),
                    const SizedBox(height: 16),
                    TextField(
                      decoration: const InputDecoration(
                        labelText: 'Recipients (comma separated)',
                        border: OutlineInputBorder(),
                      ),
                      onChanged: (value) => _recipients = value.split(','),
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 24),
            CustomButton(
              text: 'Schedule Report',
              onPressed: _scheduleReport,
            ),
          ],
        ),
      ),
    );
  }
}